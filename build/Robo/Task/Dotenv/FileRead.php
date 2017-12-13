<?php

namespace Qobo\Robo\Task\Dotenv;

use Robo\Result;
use \Qobo\Utility\File;
use \Qobo\Utility\Dotenv;

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

        $this->printInfo("Reading from {path}", $this->data);
        return $this->read($this->data['path']);
    }

    public function read($path = null)
    {
        if ($path) {
            $this->data['path'] = $path;
        }

        try {
            $content = File::read($this->data['path']);
            $this->data['data'] = Dotenv::parse($content, Dotenv::FLAG_STRICT);
        } catch (\Exception $e) {
            return Result::fromException($this, $e);
        }

        return Result::success($this, "Environment successfully read", $this->data);
    }
}
