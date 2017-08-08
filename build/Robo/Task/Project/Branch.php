<?php

namespace Qobo\Robo\Task\Project;

use Robo\Result;

/**
 * Current project branch
 *
 * ```php
 * <?php
 * $this->taskProjectBranch()
 * ->run();
 *
 * ?>
 * ```
 */
class Branch extends \Qobo\Robo\AbstractCmdTask
{
    /**
     * {@inheritdoc}
     */
    protected $data = [
        'cmd'   => 'git rev-parse --abbref-ref HEAD',
        'path'  => ['./'],
        'batch' => false
    ];

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $this->printInfo("Retrieving project branch");
        $result = parent::run();
        if (!$result->wasSuccessful()) {
            return $result;
        }

        return Result::success($this, "Successfully retrieved project branch", $this->data);
    }
}
