<?php

namespace Qobo\Robo\Task\Mysql;

use Robo\Result;

/**
 * Create Mysql database
 */
class DbCreate extends BaseQuery
{
    public function run()
    {
        $this->data['query'] = "CREATE DATABASE IF NOT EXISTS " . $this->data['db'];
        $this->data['db'] = '';

        $this->printInfo("Running MySQL {query} ", $this->data);
        return parent::run();
    }
}
