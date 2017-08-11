<?php

namespace Qobo\Robo\Command\App;

use \Qobo\Robo\AbstractCommand;

class Install extends AbstractCommand
{
    /**
     * Install a project
     *
     * @return bool true on success or false on failure
     */
    public function appInstall()
    {
        $this->preInstall();

        $result = $this->taskMysqlDbCreate()
            ->db(getenv('DB_NAME'))
            ->user(getenv('DB_ADMIN_USER'))
            ->pass(getenv('DB_ADMIN_PASS'))
            ->host(getenv('DB_HOST'))
            ->run();

        $tokens = $this->taskDotenvReload()->run()['data'];
        if (!isset($tokens['SYSTEM_COMMAND_WPCLI'])) {
            $tokens['SYSTEM_COMMAND_WPCLI'] = './vendor/bin/wp --allow-root --path=webroot/wp';
        }
        $result = $this->taskTemplateProcess()
            ->wrap('%%')
            ->tokens($tokens)
            ->src('etc/wp-cli.install')
            ->dst('etc/wp-cli.install.sh')
			->run();

		$result = $this->taskExec('/bin/bash etc/wp-cli.install.sh')->run();
        $result = $this->taskTemplateProcess()
            ->wrap('%%')
            ->tokens($tokens)
            ->src('etc/wp-cli.content')
            ->dst('etc/wp-cli.content.sh')
			->run();

		$result = $this->taskExec('/bin/bash etc/wp-cli.content.sh')->run();

		$result = $this->taskFileChmod()
			->path([getenv('CHMOD_PATH')])
			->fileMode(0664)
			->dirMode(0775)
			->recursive(true)
			->run();

		$result = $this->taskFileChown()
			->path([getenv('CHOWN_PATH')])
			->user(getenv('CHOWN_USER'))
			->recursive(true)
			->run();

		$result = $this->taskFileChgrp()
			->path([getenv('CHGRP_PATH')])
			->group(getenv('CHGRP_GROUP'))
			->recursive(true)
			->run();

        $this->postInstall();
    }

    protected function preInstall()
    {
        if (is_file('.env') && is_readable('.env')) {
            $result = $this->taskDotenvReload()
               ->path('.env')
               ->run();
        }

        // old :builder:init
        $this->versionBackup("build/version");

        $result = $this->taskProjectDotenvCreate() // old :dotenv:create
                ->env('.env')
                ->template('.env.example')
                ->run();

        $result = $this->taskDotenvReload()                // old :dotenv:reload
                ->path('.env')
                ->run();

        $env = $result->getData()['data'];

        // old :file:process
        $result = $this->taskTemplateProcess()
            ->wrap('%%')
            ->tokens($env)
            ->src(getenv('TEMPLATE_SRC'))
            ->dst(getenv('TEMPLATE_DST'))
            ->run();
    }

    protected function postInstall()
    {
        $this->versionBackup("build/version.ok");
    }

    protected function versionBackup($path)
    {
        $projectVersion = $this->getProjectVersion();
        if (file_exists($path)) {
            rename($path, "$path.bak");
        }
        file_put_contents($path, $projectVersion);
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
