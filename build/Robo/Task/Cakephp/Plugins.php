<?php

namespace Qobo\Robo\Task\Cakephp;

use Robo\Result;

/**
 * Get CakePHP loaded plugins
 *
 * ```php
 * <?php
 * $this->taskCakephpPlugins()
 * ->run();
 *
 * ?>
 * ```
 */
class Plugins extends \Qobo\Robo\AbstractCmdTask
{
    /**
     * {@inheritdoc}
     */
    protected $data = [
        'cmd'   => './bin/cake plugin loaded',
        'path'  => ['./'],
        'batch' => false
    ];

    /**
     * {@inheritdoc}
     */
    function run()
    {
        $result = parent::run();
        if (!$result->wasSuccessful()) {
            return $result;
        }

        return Result::success($this, "Command run successfully", ['data' => $result->getData()['data'][0]['output']]);
    }

}
