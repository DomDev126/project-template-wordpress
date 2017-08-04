<?php

namespace Qobo\Robo\Command\Build;


class PhpCs extends Collection
{
    /**
     * @var string $commandKey
     */
    protected $commandKey = "phpcs";

    /**
     * Run PHP Code Sniffer
     */
    public function buildPhpCs()
    {
        return $this->runCmd();
    }
}
