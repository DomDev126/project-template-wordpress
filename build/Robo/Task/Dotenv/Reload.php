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
            return Result::success($this, "File is empty, nothing to read", $this->data);
        }

        $this->data['data'] = [];
        foreach ($file as $line) {
            $line = trim($line);

            // Disregard comments
            if (strpos($line, '#') === 0) {
                continue;
            }
            // Only use non-empty lines that look like setters
            if (!preg_match('#^\s*(.*)?=(.*)?$#', $line, $matches) ) {
                continue;
            }

            // Do not fill env with lots of empty keys
            if (trim($matches[2]) === "") {
                continue;
            }
            $this->data['data'][$matches[1]] = $matches[2];

            \Dotenv::makeMutable();
            \Dotenv::setEnvironmentVariable($line);
            \Dotenv::makeImmutable();
        }

        return Result::success($this, "Successfully reloaded environment", $this->data);
    }
}
