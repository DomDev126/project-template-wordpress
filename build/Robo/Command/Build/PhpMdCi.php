<?php

namespace Qobo\Robo\Command\Build;


class PhpMdCi extends \Qobo\Robo\AbstractCommand
{
    /**
     * Run PHP Mess Detector Report
     *
     * @return true on success or false on failure
     */
    public function buildPhpMdCi()
    {
        $result = $this->taskBuildPhpMdCi()
            ->path(['./src'])
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
