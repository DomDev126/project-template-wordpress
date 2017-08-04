<?php

namespace Qobo\Robo\Command\Build;


class PhpMd extends Collection
{
    /**
     * {@inheritdoc}
     */
    protected $commandKey = "phpmd";

    /**
     * Run PHP Mess Detector
     *
     * @param string $path Path for which to run
     *
     * @return true on success or false on failure
     */
    public function buildPhpMd($path = null)
    {
        return $this->runCmd(null, $path);
    }
}
