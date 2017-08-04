<?php

namespace Qobo\Robo\Command\Build;


class PhpCpd extends Collection
{
    /**
     * {@inheritdoc}
     */
    protected $commandKey = "phpcpd";

    /**
     * Run PHP Copy-Paste Detector
     *
     * @param string $path Path for which to run
     *
     * @return true on success or false on failure
     */
    public function buildPhpCpd($path = null)
    {
        return $this->runCmd(null, $path);
    }
}
