<?php

namespace Qobo\Robo\Command\Build;


class PhpMdCi extends Collection
{
    /**
     * {@inheritdoc}
     */
    protected $commandKey = "phpmd-ci";

    /**
     * Run PHP Mess Detector Report
     *
     * @param string $path Path for which to run
     *
     * @return true on success or false on failure
     */
    public function buildPhpMdCi($path = null)
    {
        return $this->runCmd(null, $path);
    }
}
