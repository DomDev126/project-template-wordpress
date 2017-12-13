<?php

namespace Qobo\Robo\Task\Cakephp;

use Robo\Result;

/**
 * Run CakePHP Migration
 *
 * ```php
 * <?php
 * $this->ShellScript()
 * ->name('make')
 * ->param('cool')
 * ->run();
 *
 * ?>
 * ```
 */
class ShellScript extends \Qobo\Robo\AbstractCmdTask
{
    /**
     * {@inheritdoc}
     */
    protected $data = [
        'cmd'   => './bin/cake %%NAME%% %%PARAM%%',
        'path'  => ['./'],
        'batch' => false,
        'name' => null,
        'param' => null
    ];

    /**
     * {@inheritdoc}
     */
    protected $requiredData = [
        'name'
    ];

    /**
     * {@inheritdoc}
     */
    protected $tokenKeys = [
        'name',
        'param'
    ];
}
