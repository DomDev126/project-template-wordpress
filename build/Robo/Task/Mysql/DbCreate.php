<?php

namespace Qobo\Robo\Task\Mysql;

use Robo\Result;

/**
 * Create Mysql database
 */
class DbCreate extends BaseQuery
{
    /**
     * {@inheritdoc}
     */
    protected $query = "CREATE DATABASE IF NOT EXISTS %%DB%%";
}
