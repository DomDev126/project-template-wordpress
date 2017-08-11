<?php

namespace Qobo\Robo\Task\Project;

use Robo\Result;
use \Qobo\Utility\File;
use \Qobo\Utility\Dotenv;

/**
 * Current project branch
 *
 * ```php
 * <?php
 * $this->taskDotenvRead()
 * ->env('.env')
 * ->template('.env.example')
 * ->run();
 *
 * ?>
 * ```
 */
class DotenvCreate extends \Qobo\Robo\AbstractTask
{
    /**
     * {@inheritdoc}
     */
    protected $data = [
        'env'       => '.env',
        'template'  => '.env.example',
        'data'      => []
    ];

    /**
     * {@inheritdoc}
     */
    protected $requiredData = [
        'env',
        'template'
    ];

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $this->printInfo("Creating environment file {env} from {template}", $this->data);

        $result = parent::run();
        if (!$result->wasSuccessful()) {
            return $result;
        }

        $env = [];
        try {
            foreach (['template', 'env'] as $key) {

                // skip on missing
                if (!is_file($this->data[$key]) || !is_readable($this->data[$key])) {
                    continue;
                }

               $content =  File::readLines($this->data[$key]);
               $env = Dotenv::parse($content, $env, Dotenv::FLAG_REPLACE_DUPLICATES);
            }
            File::writeLines(
                $this->data['env'],
                array_map(
                    function ($name, $value) { return "$name=$value"; },
                    array_keys($env),
                    array_values($env)
                )
            );
        } catch (\Exception $e) {
            return Result::fromException($this, $e);
        }
        $this->data['data'] = $env;

        return Result::success($this, "Successfully created environment", $this->data);
    }

    protected function readFile($path)
    {
        $reader = new FileRead();

        $result = $reader
            ->path($path)
            ->read();

        if (!$result->wasSuccessful()) {
            return false;
        }

        $data = $result->getData();

        if (!isset($data['data'])) {
            return false;
        }

        $this->data['data'] = array_merge($this->data['data'], $data['data']);

        return true;
    }
}
