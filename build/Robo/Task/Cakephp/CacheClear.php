<?php

namespace Qobo\Robo\Task\Cakephp;

use Robo\Result;

/**
 * Clear CakePHP cache
 *
 * ```php
 * <?php
 * $this->taskCakephpCacheClear()
 * ->run();
 *
 * ?>
 * ```
 */
class CacheClear extends \Qobo\Robo\AbstractCmdTask
{
    /**
     * {@inheritdoc}
     */
    protected $data = [
        'cmd'   => './bin/cake clear_cache all',
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
