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
     * @param string $env Custom dotenv in KEY1=VALUE1,KEY2=VALUE2 format
     *
     * @option string $format Output format (table, list, csv, json, xml)
     * @option string $fields Limit output to given fields, comma-separated
     *
     * @return PropertyList
     *
     */
    public function projectDotenvCreate($envPath = '.env', $templatePath = '.env.example', $env = '', $opts = ['format' => 'table', 'fields' => ''])
    {
        $task = $this->taskProjectDotenvCreate()
            ->env($envPath)
            ->template($templatePath);

        $vars = explode(',', $env);
        foreach ($vars as $var) {
            $var = trim($var);
            if (preg_match('/^(.*?)=(.*?)$/', $var, $matches)) {
                $task->set($matches[1], $matches[2]);
            }
        }

        $result = $task->run();

        if (!$result->wasSuccessful()) {
            $this->exitError("Failed to run command");
        }

        $data = $result->getData()['data'];

        $lines = array_map(function ($k, $v) { return "$k=$v"; }, array_keys($data), $data);

        $result = $this->taskWriteToFile($envPath)
            ->lines($lines)
            ->run();

        if (!$result->wasSuccessful()) {
            $this->exitError("Failed to run command");
        }

        return new PropertyList($data);
    }
}
