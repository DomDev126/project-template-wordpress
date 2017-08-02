<?php

namespace Qobo\Robo\Task\Dotenv;

use Robo\Result;

/**
 * Read dotenv file
 *
 * ```php
 * <?php
 * $this->taskDotenvFileRead()
 * ->path('.env')
 * ->run();
 * ?>
 * ```
 */
class FileRead extends \Qobo\Robo\AbstractTask
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

        $this->printInfo("Reading {path} dotenv file", $this->data);
        if (!is_file($this->data['path']) || !is_readable($this->data['path'])) {
            return Result::error($this, "File does not exist or is not readable", $this->data);
        }

        $file = file($this->data['path']);
        if (empty($file)) {
            return Result::success($this, "File is empty, nothing to read");
        }

        $this->data['data'] = [];
        foreach ($file as $line) {
            $line = trim($line);
            if (!preg_match('#^(.*)?=(.*)?$#', $line, $matches)) {
                continue;
            }
            $this->data['data'][$matches[1]] = $matches[2];
        }

        return Result::success($this, "Successfully read dotenv file", $this->data);
    }
}
