<?php

namespace Qobo\Robo\Task\Git;

use Robo\Result;

/**
 * Git current project's git hash
 *
 * ```php
 * <?php
 * $this->taskGitHash()
 * ->run();
 *
 * ?>
 * ```
 */
class Hash extends \Qobo\Robo\AbstractCmdTask
{
    /**
     * {@inheritdoc}
     */
    protected $data = [
        'cmd'   => 'git log -1 --pretty=format:"%h" -C %%PATH%%',
        'path'  => ['./'],
        'batch' => false
    ];

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $this->printInfo("Retrieving repo git hash");
        $result = parent::run();
        if (!$result->wasSuccessful()) {
            return $result;
        }

        $data = $result->getData();
        if (!ctype_xdigit($data['data'][0]['message'])) {
            return Result::error($this, "Retrieved result is not a hash", $this->data);
        }

        return Result::success($this, "Successfully retrieved repo hash", $this->data);
    }
}
