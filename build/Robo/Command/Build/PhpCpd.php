<?php

namespace Qobo\Robo\Command\Build;


class PhpCpd extends \Qobo\Robo\AbstractCommand
{
    /**
     * Run PHP Copy-Paste Detector
     *
     * @return true on success or false on failure
     */
    public function buildPhpCpd()
    {
        $result = $this->taskBuildPhpCpd()
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
