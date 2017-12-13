<?php

namespace Qobo\Robo\Command\Build;


class PhpDepend extends \Qobo\Robo\AbstractCommand
{
    /**
     * Run PHP Depend software analyzer and metric tool
     *
     * @return true on success or false on failure
    */
    public function buildPhpDepend()
    {
        $result = $this->taskBuildPhpDepend()
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
