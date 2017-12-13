<?php

namespace Qobo\Robo\Command\Project;

use \Qobo\Robo\AbstractCommand;

class DotenvDelete extends AbstractCommand
{
    /**
     * Delete dotenv file
     *
     * @param string $envPath Path to dotenv file
     * @option $force Force deletion
     *
     * @return bool true on success or false on failure
     */
    public function projectDotenvDelete($envPath = '.env', $opts = ['force' => false])
    {
        if (!$opts['force']) {
            $this->say(static::MSG_NO_DELETE);
            return false;
        }
        if (!file_exists($envPath)) {
            return true;
        }

        $result = $this->taskFilesystemStack()
            ->remove($envPath)
            ->run();

        return $result->wasSuccessful();
    }
}
