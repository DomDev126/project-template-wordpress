<?php

namespace Qobo\Robo\Command\Build;


class PhpLoc extends Collection
{
    /**
     * {@inheritdoc}
     */
    protected $commandKey = "phploc";

    /**
     * Run PHP Loc analyzer and measuring tool
     *
     * @param string $path Path for which to run
     *
     * @return true on success or false on failure
     */
    public function buildPhpLoc($path = null)
    {
        return $this->runCmd(null, $path);
    }
}
