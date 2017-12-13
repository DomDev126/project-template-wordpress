<?php

namespace Qobo\Robo\Formatter;

use \Qobo\Utility\Hash;
use \Consolidation\OutputFormatters\Options\FormatterOptions;

class PropertyList extends \Consolidation\OutputFormatters\StructuredData\PropertyList
{
    public function renderCell($key, $cellData, FormatterOptions $options, $rowData)
    {
        return $this->flatten($cellData);
    }

    protected function flatten($data)
    {
        if (!is_array($data)) {
            return $data;
        }

        if (!$this->isAssoc($data)) {
            return implode(",\n", array_map([$this, "flatten" ], $data));
        }

        return implode("\n", array_map(
            function ($k, $v) { return "$k: " . $this->flatten($v); },
            array_keys($data),
            array_values($data)
        ));
    }

    protected function isAssoc($arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
