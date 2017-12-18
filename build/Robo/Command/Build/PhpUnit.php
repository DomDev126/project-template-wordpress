<?php

namespace Qobo\Robo\Command\Build;


class PhpUnit extends \Qobo\Robo\AbstractCommand
{
    /**
     * Run PHP Unit Tests
     *
     * @return bool true on success or false on failure
     */
    public function buildPhpUnit()
    {
        $result = $this->taskBuildPhpUnit()
            ->path(['./tests'])
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
