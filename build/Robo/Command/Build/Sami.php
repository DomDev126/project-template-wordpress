<?php

namespace Qobo\Robo\Command\Build;


class Sami extends Collection
{
    /**
     * {@inheritdoc}
     */
    protected $commandKey = "sami";

    /**
     * Run Sami PHP documentation generation tool
     */
    public function buildSami()
    {
        return $this->runCmd();
    }
}
