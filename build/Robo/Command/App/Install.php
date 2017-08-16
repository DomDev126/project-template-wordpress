<?php

namespace Qobo\Robo\Command\App;

use \Qobo\Robo\AbstractCommand;

class Install extends AbstractCommand
{
    /**
     * Install a wordpress project
     *
     * @return bool true on success or false on failure
     */
    public function appWpInstall()
    {
        if (!$this->preInstall()) {
            return false;
        }

        $result = $this->taskDotenvReload()->run();
        $data = $result->getData();
        if (!$result->wasSuccessful() || !isset($data['data'])) {
            return false;
        }

        // make sure DB password is either present
        // or is set to null
        $dbPass = getenv('DB_ADMIN_PASS');
        if (empty($dbPass)) {
            $dbPass = null;
        }

        $tokens = $data['data'];
        if (!isset($tokens['SYSTEM_COMMAND_WPCLI'])) {
            $tokens['SYSTEM_COMMAND_WPCLI'] = './vendor/bin/wp --allow-root --path=webroot/wp';
        }

        $result = $this->taskMysqlBaseQuery()
            ->query("SELECT NOW() AS ServerTime")
            ->user(getenv('DB_ADMIN_USER'))
            ->pass($dbPass)
            ->host(getenv('DB_HOST'))
            ->run();

        if (!$result->wasSuccessful()) {
            return false;
        }
        $this->say(implode(": ", $result->getData()['data'][0]['output']));

        $tasks = [];
        $tasks []= $this->taskMysqlDbCreate()
            ->db(getenv('DB_NAME'))
            ->user(getenv('DB_ADMIN_USER'))
            ->pass($dbPass)
            ->host(getenv('DB_HOST'));

        $tasks []= $this->taskTemplateProcess()
            ->wrap('%%')
            ->tokens($tokens)
            ->src('etc/wp-cli.install')
            ->dst('etc/wp-cli.install.sh');

        $tasks []= $this->taskExec('/bin/bash etc/wp-cli.install.sh');

        $tasks []= $this->taskTemplateProcess()
            ->wrap('%%')
            ->tokens($tokens)
            ->src('etc/wp-cli.content')
            ->dst('etc/wp-cli.content.sh');

		$tasks []= $this->taskExec('/bin/bash etc/wp-cli.content.sh');

		$tasks []= $this->taskFileChmod()
			->path([getenv('CHMOD_PATH')])
		->fileMode(0664)
			->dirMode(0775)
			->recursive(true);

		$tasks []= $this->taskFileChown()
			->path([getenv('CHOWN_PATH')])
			->user(getenv('CHOWN_USER'))
			->recursive(true);

		$tasks []= $this->taskFileChgrp()
			->path([getenv('CHGRP_PATH')])
			->group(getenv('CHGRP_GROUP'))
			->recursive(true);

        foreach ($tasks as $task) {
            $result = $task->run();
            if (!$result->wasSuccessful()) {
                return false;
            }
        }

        return $this->postInstall();
    }

    /**
     * Update a wordpress project
     *
     * @return bool true on success or false on failure
     */
    public function appWpUpdate()
    {
        if (!$this->preInstall()) {
            return false;
        }

        $result = $this->taskDotenvReload()->run();
        $data = $result->getData();
        if (!$result->wasSuccessful() || !isset($data['data'])) {
            return false;
        }

        // make sure DB password is either present
        // or is set to null
        $dbPass = getenv('DB_ADMIN_PASS');
        if (empty($dbPass)) {
            $dbPass = null;
        }

        $tokens = $data['data'];
        if (!isset($tokens['SYSTEM_COMMAND_WPCLI'])) {
            $tokens['SYSTEM_COMMAND_WPCLI'] = './vendor/bin/wp --allow-root --path=webroot/wp';
        }

        $result = $this->taskMysqlBaseQuery()
            ->query("SELECT NOW() AS ServerTime")
            ->user(getenv('DB_ADMIN_USER'))
            ->pass($dbPass)
            ->host(getenv('DB_HOST'))
            ->run();

        if (!$result->wasSuccessful()) {
            return false;
        }
        $this->say(implode(": ", $result->getData()['data'][0]['output']));

        $tasks = [];

        $tasks []= $this->taskTemplateProcess()
            ->wrap('%%')
            ->tokens($tokens)
            ->src('etc/wp-cli.update')
            ->dst('etc/wp-cli.update.sh');

        $tasks []= $this->taskMysqlDbFindReplace()
            ->search(getenv('DB_FIND'))
            ->replace(getenv('DB_REPLACE'))
            ->db(getenv('DB_NAME'))
            ->user(getenv('DB_ADMIN_USER'))
            ->pass($dbPass)
            ->host(getenv('DB_HOST'));

		$tasks []= $this->taskExec('/bin/bash etc/wp-cli.update.sh');

		$tasks []= $this->taskFileChmod()
			->path([getenv('CHMOD_PATH')])
			->fileMode(0664)
			->dirMode(0775)
			->recursive(true);

		$tasks []= $this->taskFileChown()
			->path([getenv('CHOWN_PATH')])
			->user(getenv('CHOWN_USER'))
			->recursive(true);

		$tasks []= $this->taskFileChgrp()
			->path([getenv('CHGRP_PATH')])
			->group(getenv('CHGRP_GROUP'))
			->recursive(true);

        foreach ($tasks as $task) {
            $result = $task->run();
            if (!$result->wasSuccessful()) {
                return false;
            }
        }

        return $this->postInstall();
    }

    protected function preInstall()
    {
        if (is_file('.env') && is_readable('.env') && !$this->taskDotenvReload()->path('.env')->run()->wasSuccessful()) {
            return false;
        }

        // old :builder:init
        if (!$this->versionBackup("build/version")) {
            return false;
        }

        $result = $this->taskProjectDotenvCreate() // old :dotenv:create
                ->env('.env')
                ->template('.env.example')
                ->run();

        if (!$result->wasSuccessful()) {
            return false;
        }

        $result = $this->taskDotenvReload()                // old :dotenv:reload
                ->path('.env')
                ->run();

        if (!$result->wasSuccessful()) {
            return false;
        }
        $env = $result->getData()['data'];

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
