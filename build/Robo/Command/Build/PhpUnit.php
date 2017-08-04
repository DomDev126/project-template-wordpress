<?php

namespace Qobo\Robo\Command\Build;


class PhpUnit extends Collection
{
    /**
     * {@inheritdoc}
     */
    protected $commandKey = "phpunit";

    /**
     * Run PHP Unit Tests
     */
    public function buildPhpUnit()
    {
        return $this->runCmd();
    }
}
