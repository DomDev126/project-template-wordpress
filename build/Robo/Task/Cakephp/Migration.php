<?php

namespace Qobo\Robo\Task\Cakephp;

use Robo\Result;

/**
 * Run CakePHP Migration
 *
 * ```php
 * <?php
 * $this->taskCakephpMigration()
 * ->connection('test')
 * ->plugin('somePlugin')
 * ->run();
 *
 * ?>
 * ```
 */
class Migration extends \Qobo\Robo\AbstractCmdTask
{
    /**
     * {@inheritdoc}
     */
    protected $data = [
        'cmd'   => './bin/cake migrations migrate %%PLUGIN%% %%CONNECTION%%',
        'path'  => ['./'],
        'batch' => false,
        'plugin' => null,
        'connection' => null
    ];

    /**
     * {@inheritdoc}
     */
    protected $tokenKeys = [
        ['plugin', '-p '],
        ['connection', '--connection=']
    ];
}
