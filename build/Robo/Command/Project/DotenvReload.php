<?php

namespace Qobo\Robo\Command\Project;

use \Qobo\Robo\AbstractCommand;

class DotenvReload extends AbstractCommand
{
    /**
     * Reload environment from given dotenv file
     *
     * @param string $envPath Path to dotenv file
     *
     * @return bool true on success or false on failure
     */
    public function projectDotenvReload($envPath = '.env')
    {
        // Reload
        $result = $this->taskDotenvReload()
            ->path($envPath)
            ->run();

        return $result->wasSuccessful();
    }
}
