<?php

namespace Qobo\Robo\Command\Template;

use \Qobo\Robo\AbstractCommand;
use \Qobo\Robo\Formatter\RowsOfFields;

class TokensList extends AbstractCommand
{
    /**
     * List all present tokens in a given template
     *
     * @param string $path Path to template
     * @param string $pre Token prefix
     * @param string $post Token postfix
     * @option string $format Output format (table, list, csv, json, xml)
     * @option string $fields Limit output to given fields, comma-separated
     *
     * @return RowsOfFields|false on success, false on failure
     */
    public function templateTokensList($path, $pre = '%%', $post = '%%', $opts = ['format' => 'table', 'fields' => ''])
    {
        $result = $this->taskTemplateFileTokensList()
            ->path($path)
            ->pre($pre)
            ->post($post)
            ->run();

        if (!$result->wasSuccessful()) {
            return false;
        }

        $data = $result->getData();

        if (empty($data) || !isset($data['data'])) {
            return new RowsOfFields([]);
        }

        natsort($data['data']);
        $data['data'] = array_map(function ($item) { return ['Token' => $item]; }, $data['data']);
        return new RowsOfFields($data['data']);
    }
}
