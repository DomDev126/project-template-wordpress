<?php

namespace Qobo\Robo\Command\Build;


class PhpCs extends \Qobo\Robo\AbstractCommand
{
    /**
     * Run PHP Code Sniffer
     *
     * return bool true on success or false on failure
     */
    public function buildPhpCs()
    {
        $result = $this->taskBuildPhpCs()
            ->path(['./src', './tests'])
            ->run();

        if (!$result->wasSuccessful()) {
            $this->exitError("Failed to run command");
        }

        foreach ($result->getData()['data'][0]['output'] as $line) {
            $this->say($line);
        }

        return true;
    }
}
