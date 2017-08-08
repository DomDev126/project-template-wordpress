<?php

namespace Qobo\Robo\Command\Build;


class PhpLoc extends \Qobo\Robo\AbstractCommand
{
    /**
     * Run PHP Loc analyzer and measuring tool
     *
     * @return true on success or false on failure
     */
    public function buildPhpLoc()
    {
        $result = $this->taskBuildPhpLoc()
            ->path(['./src', './tests'])
            ->run();

        if (!$result->wasSuccessful()) {
            return false;
        }

        foreach ($result->getData()['data'][0]['output'] as $line) {
            $this->say($line);
        }

        return true;
    }
}
