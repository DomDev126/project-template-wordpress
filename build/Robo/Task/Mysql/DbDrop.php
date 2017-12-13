<?php

namespace Qobo\Robo\Task\Mysql;

use Robo\Result;

/**
 * Drop Mysql database
 */
class DbDrop extends BaseQuery
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
    protected $query = "DROP DATABASE IF EXISTS %%DB%%";
}
