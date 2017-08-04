<?php

namespace Qobo\Robo\Command\Template;

use \Qobo\Robo\AbstractCommand;
use \Qobo\Robo\Formatter\PropertyList;

class Parse extends AbstractCommand
{
    /**
     * Parses given template with tokens made of env
     *
     * @param string $path Path to template
     * @option string $format Output format (table, list, csv, json, xml)
     * @option string $fields Limit output to given fields, comma-separated
     *
     * @return PropertyList|false on success, false on failure
     */
    public function templateParse($path, $opts = ['format' => 'table', 'fields' => ''])
    {
        $tokens = [];
        $result = $this->taskDotenvFileRead()
            ->path('.env.example')
            ->run();
        if ($result->wasSuccessful()) {
            $data = $result->getData();
            if (isset($data['data'])) {
                $tokens = $data['data'];
            }
        }

        $result = $this->taskDotenvFileRead()
            ->path('.env')
            ->run();
        if ($result->wasSuccessful()) {
            $data = $result->getData();
            if (isset($data['data'])) {
                $tokens = array_merge($tokens, $data['data']);
            }
        }

        $result = $this->taskTemplateFileParse()
            ->path($path)
            ->wrap('%%')
            ->tokens($tokens)
            ->run();

        if (!$result->wasSuccessful()) {
            return false;
        }

        $data = $result->getData();

        return new PropertyList($data);
    }
}
