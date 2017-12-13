<?php

namespace Qobo\Robo\Task\Dotenv;

use Robo\Result;
use \Qobo\Utility\File;
use \Qobo\Utility\Dotenv;

/**
 * Reload environment from dotenv file
 *
 * ```php
 * <?php
 * $this->taskDotenvReload()
 * ->path('.env')
 * ->run();
 * ?>
 * ```
 */
class Reload extends \Qobo\Robo\AbstractTask
{
    /**
     * {@inheritdoc}
     */
    protected $data = [
        'path'          => '.env',
    ];

    /**
     * {@inheritdoc}
     */
    protected $requiredData = [
        'path',
    ];

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $result = parent::run();
        if (!$result->wasSuccessful()) {
            return $result;
        }

        $this->printInfo("Reloading environment from {path} dotenv file", $this->data);

        try {
            $content = File::readLines($this->data['path']);
            $dotenv = Dotenv::parse($content);
            $this->data['data'] = Dotenv::apply($dotenv, [], Dotenv::FLAG_REPLACE_DUPLICATES);
        } catch (\Exception $e) {
            return Result::fromException($this, $e);
        }

        return Result::success($this, "Successfully reloaded environment", $this->data);
    }
}
