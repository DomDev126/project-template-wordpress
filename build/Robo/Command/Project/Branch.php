<?php

namespace Qobo\Robo\Command\Project;

use \Qobo\Robo\AbstractCommand;
use \Qobo\Robo\Formatter\PropertyList;

class Branch extends AbstractCommand
{
    /**
     * Get current project branch
     *
     * @return string branch
     */
    public function projectBranch($opts = ['format' => 'table', 'fields' => ''])
    {
        $result = $this->taskProjectBranch()
            ->run();

        if (!$result->wasSuccessful()) {
            $this->exitError("Failed to run command");
        }

        $data = $result->getData();
        return new PropertyList(['branch' => $data['data'][0]['message']]);
    }
}
