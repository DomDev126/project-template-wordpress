<?php

namespace Qobo\Robo\Command\Cakephp;

use \Qobo\Robo\AbstractCommand;
use \Qobo\Robo\Formatter\PropertyList;

class Plugins extends AbstractCommand
{
    /**
     * List CakePHP loaded plugins
     *
     * @option string $format Output format (table, list, csv, json, xml)
     * @option string $fields Limit output to given fields, comma-separated
     *
     * @return PropertyList result
     */
    public function cakephpPlugins($opts = ['format' => 'table', 'fields' => ''])
    {
        $result = $this->taskCakephpPlugins()
            ->run();
        if (!$result->wasSuccessful()) {
            return false;
        }

        return new PropertyList($result->getData()['data']);
    }
}
