<?php

namespace Qobo\Robo\Command\Template;

use \Qobo\Robo\AbstractCommand;
use \Qobo\Robo\Formatter\PropertyList;

class Process extends AbstractCommand
{
    /**
     * Process given template with tokens from environment variables
     *
     * @param string $src Path to template
     * @param string $dst Path to final file
     * @option string $format Output format (table, list, csv, json, xml)
     * @option string $fields Limit output to given fields, comma-separated
     *
     * @return bool true on success, false on failure
     */
    public function templateProcess($src, $dst, $opts = ['format' => 'table', 'fields' => ''])
    {
        $result = $this->taskDotenvFileRead()
            ->path('.env')
            ->run();
        if (!$result->wasSuccessful()) {
            return false;
        }

        $data = $result->getData();
        if (!isset($data['data'])) {
            return false;
        }

        return $this->taskTemplateProcess()
            ->src($src)
            ->dst($dst)
            ->wrap('%%')
            ->tokens($tokens)
            ->run()
            ->wasSuccessful();
    }
}
