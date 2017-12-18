<?php

namespace Qobo\Robo\Command\Build;


class Sami extends \Qobo\Robo\AbstractCommand
{
    /**
     * Run Sami PHP documentation generation tool
     *
     * @return bool true on success or false on failure
     */
    public function buildSami()
    {
        $result = $this->taskBuildSami()
            ->path(['./src'])
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
