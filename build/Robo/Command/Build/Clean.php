<?php

namespace Qobo\Robo\Command\Build;


class Clean extends \Qobo\Robo\AbstractCommand
{
    /**
     * Clean all after build
     *
     * @return bool true on success or false on failure
     */
    public function buildClean()
    {
        $result = $this->taskBuildClean()
            ->run();

        if (!$result->wasSuccessful()) {
            $this->exitError("Failed to run command");
        }

        $data = $result->getData();
        foreach ($data['data'] as $dir) {
            $this->say("Cleaned <info>$dir</info>");
        }
        return true;

    }
}
