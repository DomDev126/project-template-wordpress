<?php

namespace Qobo\Robo\Task\Mysql;

class DbFindReplace extends \Qobo\Robo\AbstractCmdTask
{
    /**
     * {@inheritdoc}
     */
    protected $data = [
        'cmd'   => './vendor/interconnectit/search-replace-db/srdb.cli.php -h %%HOST%% %%PORT%% -u %%USER%% -p %%PASS%% -n %%DB%% -s %%SEARCH%% -r %%REPLACE%%',
        'path'  => ['./'],
        'host'  => 'localhost',
        'user'  => 'root',
        'pass'  => '',
        'port'  => null,
        'db'    => null,
        'search'    => null,
        'replace'   => null,
        'batch' => false
    ];

    /**
     * {@inheritdoc}
     */
    protected $requiredData = [
        'cmd',
        'path',
        'host',
        'user',
        'pass',
        'db',
        'search',
        'replace',
        'batch'
    ];

    /**
     * {@inheritdoc}
     */
    protected $tokenKeys = [
        'host',
        'user',
        'pass',
        'db',
        'search',
        'replace',
        ['port', '--port ']
    ];
}
