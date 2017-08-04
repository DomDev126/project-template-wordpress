<?php

namespace Qobo\Robo\Command\Project;

use \Qobo\Robo\AbstractCommand;
use \Qobo\Robo\Formatter\PropertyList;

class DotenvCreate extends AbstractCommand
{
    /**
     * Create dotenv file
     *
     * @param string $envPath Path to dotenv file
     * @param string $templatePath Path to dotenv template
     *
     * @option string $format Output format (table, list, csv, json, xml)
     * @option string $fields Limit output to given fields, comma-separated
     *
     * @return PropertyList
     *
     */
    public function projectDotenvCreate($envPath = '.env', $templatePath = '.env.example', $opts = ['format' => 'table', 'fields' => ''])
    {
        $env = [];

        // Read the template if any
        $result = $this->taskDotenvFileRead()
            ->path($templatePath)
            ->run();
        if ($result->wasSuccessful()) {
            $data = $result->getData();
            if (isset($data['data'])) {
                $env = $data['data'];
            }
        }

        // Read the existing .env if any
        $result = $this->taskDotenvFileRead()
            ->path($envPath)
            ->run();
        if ($result->wasSuccessful()) {
            $data = $result->getData();
            if (isset($data['data'])) {
                $env = array_merge($env, $data['data']);
            }
        }

        $lines = array_map(function ($k, $v) { return "$k=$v"; }, array_keys($env), $env);

        $result = $this->taskWriteToFile($envPath)
            ->lines($lines)
            ->run();

        return new PropertyList($env);
    }
}
