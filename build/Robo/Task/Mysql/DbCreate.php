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
    protected $data = [
        'cmd'   => 'mysql %%HOST%% %%PORT%% %%USER%% %%PASS%% %%QUERY%%',
        'path'  => ['./'],
        'batch' => false,
        'user'  => 'root',
        'pass'  => null,
        'host'  => null,
        'port'  => null,
        'query' => null,
        'db'    => null
    ];


    /**
     * {@inheritdoc}
     */
    protected $query = "CREATE DATABASE IF NOT EXISTS %%DB%%";
}
