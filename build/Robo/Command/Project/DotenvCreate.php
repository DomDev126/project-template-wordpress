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
        $result = $this->taskProjectDotenvCreate()
            ->env($envPath)
            ->template($templatePath)
            ->run();

        $data = $result->getData()['data'];

        $lines = array_map(function ($k, $v) { return "$k=$v"; }, array_keys($data), $data);

        $result = $this->taskWriteToFile($envPath)
            ->lines($lines)
            ->run();

        return new PropertyList($data);
    }
}
