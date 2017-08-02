<?php

namespace Qobo\Robo\Task\Dotenv;

use Robo\Result;

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
        if (!is_file($this->data['path']) || !is_readable($this->data['path'])) {
            return Result::error($this, "File does not exist or is not readable", $this->data);
        }

        $file = file($this->data['path']);
        if (empty($file)) {
            return Result::success($this, "File is empty, nothing to read");
        }

        if (preg_match("/^((.*?)\/)(.*)$/", $this->data['path'], $matches)) {
            $dir = $matches[2];
            $file = $matches[3];
        } else {
            $dir = "./";
            $file = $this->data['path'];
        }

        \Dotenv::makeMutable();
        \Dotenv::load($dir, $file);
        \Dotenv::makeImmutable();

        return Result::success($this, "Successfully reloaded environment");
    }
}
