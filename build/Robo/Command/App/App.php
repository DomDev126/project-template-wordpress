<?php

namespace Qobo\Robo\Command\App;

use \Qobo\Robo\AbstractCommand;

class App extends AbstractCommand
{

    /**
     * @var array $defaultEnv Default values if missing in env
     */
    protected $defaultEnv = [
        'SYSTEM_COMMAND_WPCLI'  => './vendor/bin/wp --allow-root --path=webroot/wp'
    ];

    /**
     * Install a project
     *
     * @return bool true on success or false on failure
     */
    public function appInstall()
    {
        $env = $this->getDotenv();

        if ($env === false || !$this->preInstall($env)) {
            return false;
        }

        if (preg_match('/^qobrix.*$/', $env['PLATFORM'])) {
            $result = $this->installCake($env);
        } else if (preg_match('/^wordpress.*$/', $env['PLATFORM'])) {
            $result = $this->installWp($env);
        } else {
            $result = true;
        }

        if (!$result) {
            return false;
        }

        return $this->postInstall();
    }

    /**
     * Update a project
     *
     * @return bool true on success or false on failure
     */
    public function appUpdate()
    {
        $env = $this->getDotenv();

        if ($env === false || !$this->preInstall($env)) {
            return false;
        }

        if (preg_match('/^qobrix.*$/', $env['PLATFORM'])) {
            $result = $this->installCake($env);
        } else if (preg_match('/^wordpress.*$/', $env['PLATFORM'])) {
            $result = $this->installWp($env);
        } else {
            $result = true;
        }

        if (!$result) {
            return false;
        }

        return $this->postInstall();
    }

    /**
     * Remove a project
     *
     * @return bool true on success or false on failure
     */
    public function appRemove()
    {
        $env = $this->getDotenv();

        // drop test database
        $result = $this->taskMysqlDbDrop()
            ->db($this->getValue('DB_NAME', $env) . '_test')
            ->user($this->getValue('DB_ADMIN_USER', $env))
            ->pass($this->getValue('DB_ADMIN_PASS', $env))
            ->host($this->getValue('DB_HOST', $env))
            ->run();

        if (!$result->wasSuccessful()) {
            return false;
        }

        // drop project database
        $result = $this->taskMysqlDbDrop()
            ->db($this->getValue('DB_NAME', $env))
            ->user($this->getValue('DB_ADMIN_USER', $env))
            ->pass($this->getValue('DB_ADMIN_PASS', $env))
            ->host($this->getValue('DB_HOST', $env))
            ->run();

        if (!$result->wasSuccessful()) {
            return false;
        }

        // Remove .env
        return (file_exists('.env') && !unlink('.env')) ? false : true;
    }

    /**
     * Do wordpress related install things
     *
     * @param array $env Environment variables
     * @return bool true on success or false on failure
     */
    protected function installWp($env)
    {
        // Check DB connectivity and get server time
        $result = $this->taskMysqlBaseQuery()
            ->query("SELECT NOW() AS ServerTime")
            ->user($this->getValue('DB_ADMIN_USER', $env))
            ->pass($this->getValue('DB_ADMIN_PASS', $env))
            ->host($this->getValue('DB_HOST', $env))
            ->run();

        if (!$result->wasSuccessful()) {
            return false;
        }
        $this->say(implode(": ", $result->getData()['data'][0]['output']));

        // prepare all remaining tasks in this array
        $tasks = [];

        // create DB
        $tasks []= $this->taskMysqlDbCreate()
            ->db($this->getValue('DB_NAME', $env))
            ->user($this->getValue('DB_ADMIN_USER', $env))
            ->pass($this->getValue('DB_ADMIN_PASS', $env))
            ->host($this->getValue('DB_HOST', $env));

        // Parse install script template
        $tasks []= $this->taskTemplateProcess()
            ->wrap('%%')
            ->tokens($env)
            ->src('etc/wp-cli.install')
            ->dst('etc/wp-cli.install.sh');

        // Run install script
        $tasks []= $this->taskExec('/bin/bash etc/wp-cli.install.sh');

        // Parse content script template
        $tasks []= $this->taskTemplateProcess()
            ->wrap('%%')
            ->tokens($env)
            ->src('etc/wp-cli.content')
            ->dst('etc/wp-cli.content.sh');

        // Run content script
		$tasks []= $this->taskExec('/bin/bash etc/wp-cli.content.sh');

        // Chmod dir
		$tasks []= $this->taskFileChmod()
			->path([$this->getValue('CHMOD_PATH', $env)])
		    ->fileMode(0664)
			->dirMode(0775)
			->recursive(true);

        // Chown dir
		$tasks []= $this->taskFileChown()
			->path([$this->getValue('CHOWN_PATH', $env)])
			->user($this->getValue('CHOWN_USER', $env))
			->recursive(true);

        // Chgrp dir
		$tasks []= $this->taskFileChgrp()
			->path([$this->getValue('CHGRP_PATH', $env)])
			->group($this->getValue('CHGRP_GROUP', $env))
			->recursive(true);

        // Now as we have all tasks prepared in order,
        // run one-by-one and stop on first fail
        foreach ($tasks as $task) {
            $result = $task->run();
            if (!$result->wasSuccessful()) {
                return false;
            }
        }

        // shoul be ok by here
        return true;
    }


    /**
     * Update a wordpress project
     *
     * @param array $env Environment variables
     * @return bool true on success or false on failure
     */
    public function updateWp($env)
    {
        $result = $this->taskMysqlBaseQuery()
            ->query("SELECT NOW() AS ServerTime")
            ->user($this->getValue('DB_ADMIN_USER', $env))
            ->pass($this->getValue('DB_ADMIN_PASS', $env))
            ->host($this->getValue('DB_HOST', $env))
            ->run();

        if (!$result->wasSuccessful()) {
            return false;
        }
        $this->say(implode(": ", $result->getData()['data'][0]['output']));

        $tasks = [];

        $tasks []= $this->taskTemplateProcess()
            ->wrap('%%')
            ->tokens($env)
            ->src('etc/wp-cli.update')
            ->dst('etc/wp-cli.update.sh');

        $tasks []= $this->taskMysqlDbFindReplace()
            ->search($this->getValue('DB_FIND', $env))
            ->replace($this->getValue('DB_REPLACE', $env))
            ->db($this->getValue('DB_NAME', $env))
            ->user($this->getValue('DB_ADMIN_USER', $env))
            ->pass($this->getValue('DB_ADMIN_PASS', $env))
            ->host($this->getValue('DB_HOST', $env));

		$tasks []= $this->taskExec('/bin/bash etc/wp-cli.update.sh');

		$tasks []= $this->taskFileChmod()
			->path([$this->getValue('CHMOD_PATH', $env)])
			->fileMode(0664)
			->dirMode(0775)
			->recursive(true);

		$tasks []= $this->taskFileChown()
			->path([$this->getValue('CHOWN_PATH', $env)])
			->user($this->getValue('CHOWN_USER', $env))
			->recursive(true);

		$tasks []= $this->taskFileChgrp()
			->path([$this->getValue('CHGRP_PATH', $env)])
			->group($this->getValue('CHGRP_GROUP', $env))
			->recursive(true);

        foreach ($tasks as $task) {
            $result = $task->run();
            if (!$result->wasSuccessful()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Do CakePHP related install things
     *
     * @return bool true on success or false on failure
     */
    protected function installCake($env)
    {
        // Check DB connectivity and get server time
        $result = $this->taskMysqlBaseQuery()
            ->query("SELECT NOW() AS ServerTime")
            ->user($this->getValue('DB_ADMIN_USER', $env))
            ->pass($this->getValue('DB_ADMIN_PASS', $env))
            ->host($this->getValue('DB_HOST', $env))
            ->run();

        if (!$result->wasSuccessful()) {
            return false;
        }
        $this->say(implode(": ", $result->getData()['data'][0]['output']));

        // prepare all remaining tasks in this array
        $tasks = [];

        // create DB
        $tasks []= $this->taskMysqlDbCreate()
            ->db($this->getValue('DB_NAME', $env))
            ->user($this->getValue('DB_ADMIN_USER', $env))
            ->pass($this->getValue('DB_ADMIN_PASS', $env))
            ->host($this->getValue('DB_HOST', $env));


        // drop test DB
        $tasks []= $this->taskMysqlDbDrop()
             ->db($this->getValue('DB_NAME', $env) . "_test")
             ->user($this->getValue('DB_ADMIN_USER', $env))
             ->pass($this->getValue('DB_ADMIN_PASS', $env))
             ->host($this->getValue('DB_HOST', $env));

        // create test DB
        $tasks []= $this->taskMysqlDbCreate()
            ->db($this->getValue('DB_NAME', $env) . "_test")
            ->user($this->getValue('DB_ADMIN_USER', $env))
            ->pass($this->getValue('DB_ADMIN_PASS', $env))
            ->host($this->getValue('DB_HOST', $env));

        // get a list of cakephp plugins
        $result = $this->taskCakephpPlugins()->run();
        if (!$result->wasSuccessful()) {
            return false;
        }
        $plugins = $result->getData()['data'];

        // test plugin migrations
        foreach ($plugins as $plugin) {
            $tasks []= $this->taskCakephpMigration()
                ->connection('test')
                ->plugin($plugin);
        }

        // test app migration
        $tasks []= $this->taskCakephpMigration()
            ->connection('test');

        // drop test DB
        $tasks []= $this->taskMysqlDbDrop()
             ->db($this->getValue('DB_NAME', $env) . "_test")
             ->user($this->getValue('DB_ADMIN_USER', $env))
             ->pass($this->getValue('DB_ADMIN_PASS', $env))
             ->host($this->getValue('DB_HOST', $env));

        // create test DB
        $tasks []= $this->taskMysqlDbCreate()
            ->db($this->getValue('DB_NAME', $env) . "_test")
            ->user($this->getValue('DB_ADMIN_USER', $env))
            ->pass($this->getValue('DB_ADMIN_PASS', $env))
            ->host($this->getValue('DB_HOST', $env));

        // do plugin migrations
        foreach ($plugins as $plugin) {
            $tasks []= $this->taskCakephpMigration()
                ->plugin($plugin);
        }

        // do app migrations
        $tasks []= $this->taskCakephpMigration();

        $tasks []= $this->taskCakephpAdminAdd()
            ->username($this->getValue('DEV_USER', $env))
            ->password($this->getValue('DEV_PASS', $env))
            ->email($this->getValue('DEV_EMAIL', $env));

        $shellScripts = [
           'fix_null_dates',
            'group import',
            'group assign',
            'role import',
            'capability assign',
            'menu import',
            'add_dblist_permissions',
            'dblists_add',
        ];
        foreach ($shellScripts as $script) {
            if (strstr($script, " ")) {
                list($name, $param) = explode(" ", $script);
                $tasks []= $this->taskCakephpShellScript()->name($name)->param($param);
            } else {
                $tasks []= $this->taskCakephpShellScript()->name($script);
            }
        }

        $paths = [
            'tmp',
            'logs',
            'webroot/uploads'
        ];
        $dirMode = (!empty($this->getValue('CHMOD_DIR_MODE', $env))
            ? $this->getValue('CHMOD_DIR_MODE', $env)
            : 0775
        );
        $fileMode = (!empty($this->getValue('CHMOD_FILE_MODE', $env))
            ? $this->getValue('CHMOD_FILE_MODE', $env)
            : 0664
        );
        $user = $this->getValue('CHOWN_USER', $env);
        $group = $this->getValue('CHGRP_GROUP', $env);

        foreach ($paths as $path) {

            $path = str_replace("build/Robo/Command/App", "",  __DIR__) . $path;
            if (!file_exists($path)) {
                continue;
            }

            // Chmod dir
            $tasks []= $this->taskFileChmod()
                ->path([$path])
                ->fileMode($fileMode)
                ->dirMode($dirMode)
                ->recursive(true);

            // Chown dir
            $tasks []= $this->taskFileChown()
                ->path([$path])
                ->user($user)
                ->recursive(true);

            // Chgrp dir
            $tasks []= $this->taskFileChgrp()
                ->path([$path])
                ->group($group)
                ->recursive(true);
        }

        // execute all tasks
        foreach ($tasks as $task) {
            $result = $task->run();
            if (!$result->wasSuccessful()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Do CakePHP related update things
     *
     * @return bool true on success or false on failure
     */
    protected function updateCake($env)
    {
        // Check DB connectivity and get server time
        $result = $this->taskMysqlBaseQuery()
            ->query("SELECT NOW() AS ServerTime")
            ->user($this->getValue('DB_ADMIN_USER', $env))
            ->pass($this->getValue('DB_ADMIN_PASS', $env))
            ->host($this->getValue('DB_HOST', $env))
            ->run();

        if (!$result->wasSuccessful()) {
            return false;
        }
        $this->say(implode(": ", $result->getData()['data'][0]['output']));

        // prepare all remaining tasks in this array
        $tasks = [];

        // drop test DB
        $tasks []= $this->taskMysqlDbDrop()
             ->db($this->getValue('DB_NAME', $env) . "_test")
             ->user($this->getValue('DB_ADMIN_USER', $env))
             ->pass($this->getValue('DB_ADMIN_PASS', $env))
             ->host($this->getValue('DB_HOST', $env));

        // create test DB
        $tasks []= $this->taskMysqlDbCreate()
            ->db($this->getValue('DB_NAME', $env) . "_test")
            ->user($this->getValue('DB_ADMIN_USER', $env))
            ->pass($this->getValue('DB_ADMIN_PASS', $env))
            ->host($this->getValue('DB_HOST', $env));

        // get a list of cakephp plugins
        $result = $this->taskCakephpPlugins()->run();
        if (!$result->wasSuccessful()) {
            return false;
        }
        $plugins = $result->getData()['data'];

        // test plugin migrations
        foreach ($plugins as $plugin) {
            $tasks []= $this->taskCakephpMigration()
                ->connection('test')
                ->plugin($plugin);
        }

        // test app migration
        $tasks []= $this->taskCakephpMigration()
            ->connection('test');

        // drop test DB
        $tasks []= $this->taskMysqlDbDrop()
             ->db($this->getValue('DB_NAME', $env) . "_test")
             ->user($this->getValue('DB_ADMIN_USER', $env))
             ->pass($this->getValue('DB_ADMIN_PASS', $env))
             ->host($this->getValue('DB_HOST', $env));

        // create test DB
        $tasks []= $this->taskMysqlDbCreate()
            ->db($this->getValue('DB_NAME', $env) . "_test")
            ->user($this->getValue('DB_ADMIN_USER', $env))
            ->pass($this->getValue('DB_ADMIN_PASS', $env))
            ->host($this->getValue('DB_HOST', $env));

        $tasks [] = $this->taskCakephpCacheClear();

        // do plugin migrations
        foreach ($plugins as $plugin) {
            $tasks []= $this->taskCakephpMigration()
                ->plugin($plugin);
        }

        // do app migrations
        $tasks []= $this->taskCakephpMigration();

        $shellScripts = [
           'fix_null_dates',
            'group import',
            'group assign',
            'role import',
            'capability assign',
            'menu import',
            'add_dblist_permissions',
            'dblists_add',
        ];
        foreach ($shellScripts as $script) {
            if (strstr($script, " ")) {
                list($name, $param) = explode(" ", $script);
                $tasks []= $this->taskCakephpShellScript()->name($name)->param($param);
            } else {
                $tasks []= $this->taskCakephpShellScript()->name($script);
            }
        }

        $paths = [
            'tmp',
            'logs',
            'webroot/uploads'
        ];
        $dirMode = (!empty($this->getValue('CHMOD_DIR_MODE', $env))
            ? $this->getValue('CHMOD_DIR_MODE', $env)
            : 0775
        );
        $fileMode = (!empty($this->getValue('CHMOD_FILE_MODE', $env))
            ? $this->getValue('CHMOD_FILE_MODE', $env)
            : 0664
        );
        $user = $this->getValue('CHOWN_USER', $env);
        $group = $this->getValue('CHGRP_GROUP', $env);

        foreach ($paths as $path) {

            $path = str_replace("build/Robo/Command/App", "",  __DIR__) . $path;
            if (!file_exists($path)) {
                continue;
            }

            // Chmod dir
            $tasks []= $this->taskFileChmod()
                ->path([$path])
                ->fileMode($fileMode)
                ->dirMode($dirMode)
                ->recursive(true);

            // Chown dir
            $tasks []= $this->taskFileChown()
                ->path([$path])
                ->user($user)
                ->recursive(true);

            // Chgrp dir
            $tasks []= $this->taskFileChgrp()
                ->path([$path])
                ->group($group)
                ->recursive(true);
        }

        // execute all tasks
        foreach ($tasks as $task) {
            $result = $task->run();
            if (!$result->wasSuccessful()) {
                return false;
            }
        }


        return true;
    }


    /**
     * Recreates and reloads environment
     *
     * @return mixed Env array or false on failure
     */
    protected function getDotenv()
    {
        $batch = $this->collectionBuilder();

        $result = $batch->taskProjectDotenvCreate()
                ->env('.env')
                ->template('.env.example')
            ->taskDotenvReload()
                ->path('.env')
            ->run();

        if (!$result->wasSuccessful()) {
            return false;
        }

        return $result->getData()['data'];
    }

    /**
     * Find a value for configuration parameter
     *
     * @param string $name Parameter name
     * @param array $env Environment
     *
     * @return string
     */
    protected function getValue($name, $env)
    {
        // try to match in given $env
        if (!empty($env) && isset($env[$name])) {
            return $env[$name];
        }

        // look in real ENV
        $value = getenv($name);
        if ($value !== false) {
            return $value;
        }

        // look in the defaults
        if (!empty($this->defaultEnv) && isset($this->defaultEnv[$name])) {
            return $this->defaultEnv[$name];
        }

        // return null if nothing
        return null;
    }

    protected function preInstall($env)
    {
        // old :builder:init
        if (!$this->versionBackup("build/version")) {
            return false;
        }

        // old :file:process
        return $this->taskTemplateProcess()
            ->wrap('%%')
            ->tokens($env)
            ->src(getenv('TEMPLATE_SRC'))
            ->dst(getenv('TEMPLATE_DST'))
            ->run()
            ->wasSuccessful();
    }

    protected function postInstall()
    {
        return $this->versionBackup("build/version.ok");
    }

    protected function versionBackup($path)
    {
        $projectVersion = $this->getProjectVersion();
        if (file_exists($path)) {
            rename($path, "$path.bak");
        }
        return (file_put_contents($path, $projectVersion) === false) ? false : true;
    }

    protected function getProjectVersion()
    {
        $envVersion = getenv('GIT_BRANCH');
        if (!empty($envVersion)) {
            return $envVersion;
        }

        $result = $this->taskGitHash()->run();
        if ($result->wasSuccessful()) {
            return $result->getData()['data'][0]['message'];
        }
        return "Unknown";
    }
}
