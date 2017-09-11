<?php

namespace Qobo\Robo\Task\Cakephp;

use Robo\Result;

/**
 * Run CakePHP Migration
 *
 * ```php
 * <?php
 * $this->taskCakephpAdminAdd()
 * ->username('vasja')
 * ->password('pupkin')
 * ->email('vasja@pupkin.me')
 * ->run();
 *
 * ?>
 * ```
 */
class AdminAdd extends \Qobo\Robo\AbstractCmdTask
{
    /**
     * {@inheritdoc}
     */
    protected $data = [
        'cmd'   => './bin/cake users addSuperuser %%USERNAME%% %%PASSWORD%% %%EMAIL%%',
        'path'  => ['./'],
        'batch' => false,
        'username' => null,
        'password' => null,
        'email' => null
    ];

    /**
     * {@inheritdoc}
     */
    protected $requiredData = [
        'username',
        'password'
    ];

    /**
     * {@inheritdoc}
     */
    protected $tokenKeys = [
        ['username', '--username='],
        ['password', '--password=']
    ];
}
