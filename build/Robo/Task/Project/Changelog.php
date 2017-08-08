<?php

namespace Qobo\Robo\Task\Project;

use Robo\Result;

/**
 * Git current project changelog
 *
 * ```php
 * <?php
 * $this->taskProjectChangelog()
 * ->format('--reverse --no-merges --pretty=format:"* %<(72,trunc)%s (%ad, $an)" --date=short')
 * ->run();
 *
 * ?>
 * ```
 */
class Changelog extends \Qobo\Robo\AbstractCmdTask
{
    /**
     * {@inheritdoc}
     */
    protected $data = [
        'cmd'   => 'git log --reverse --no-merges --pretty=format:"* %<(72,trunc)%s (%ad, %an)" --date=short',
        'path'  => ['./'],
        'batch' => false
    ];

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $this->printInfo("Retrieving project changelog");
        $result = parent::run();
        if (!$result->wasSuccessful()) {
            return $result;
        }

        return Result::success($this, "Successfully retrieved project changelog", $this->data);
    }
}
