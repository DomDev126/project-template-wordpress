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
     * @param string $env Custom env in KEY1=VALUE1,KEY2=VALUE2 format
     *
     * @return bool true on success or false on failure
     */
    public function appInstall($env = '')
    {
        $env = $this->getDotenv($env);

        if ($env === false || !$this->preInstall($env)) {
            return false;
        }

        $result = $this->installWp($env);

        if (!$result) {
            return false;
        }

        return $this->postInstall();
    }

    /**
     * Update a project
     *
     * @param string $env Custom env in KEY1=VALUE1,KEY2=VALUE2 format
     *
     * @return bool true on success or false on failure
     */
    public function appUpdate($env = '')
    {
        $env = $this->getDotenv($env);

        if ($env === false || !$this->preInstall($env)) {
            return false;
        }

        $result = $this->updateWp($env);

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
     * Recreates and reloads environment
     *
     * @param string $env Custom env in KEY1=VALUE1,KEY2=VALUE2 format
     *
     * @return mixed Env array or false on failure
     */
    protected function getDotenv($env = '')
    {
        $batch = $this->collectionBuilder();


        $task = $batch->taskProjectDotenvCreate()
            ->env('.env')
            ->template('.env.example');

        $vars = explode(',', $env);
        foreach ($vars as $var) {
            $var = trim($var);
            if (preg_match('/^(.*?)=(.*?)$/', $var, $matches)) {
                $task->set($matches[1], $matches[2]);
            }
        }


        $result = $task->taskDotenvReload()
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
