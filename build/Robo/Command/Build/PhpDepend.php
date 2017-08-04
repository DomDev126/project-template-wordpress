<?php

namespace Qobo\Robo\Command\Build;


class PhpDepend extends Collection
{
    /**
     * {@inheritdoc}
     */
    protected $commandKey = "pdepend";

    /**
     * Run PHP Depend software analyzer and metric tool
     *
     * @param string $path Path for which to run
     *
     * @return true on success or false on failure
    */
    public function buildPhpDepend($path = null)
    {
        return $this->runCmd(null, $path);
    }
}
