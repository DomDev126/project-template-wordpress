<?php

namespace Qobo\Robo\Task\Mysql;

use Robo\Result;
use \Qobo\Utility\Template;

/**
 * Base Query Mysql class
 */
class BaseQuery extends \Qobo\Robo\AbstractCmdTask
{
    /**
     * {@inheritdoc}
     */
    protected $data = [
        'cmd'   => 'mysql %%HOST%% %%PORT%% %%USER%% %%PASS%% %%DB%% %%QUERY%%',
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
    protected $requiredData = [
        'cmd',
        'user',
        'query'
    ];

    /**
     * {@inheritdoc}
     */
    protected $tokenKeys = [
        ['query', '-e '],
        ['host',  '-h '],
        ['port',  '-P '],
        ['user',  '-u '],
        ['pass',  '-p' ],
        'db'
    ];

    /**
     * Query to run
     */
    protected $query = "";

    /**
     * {@inhertidoc}
     */
    public function run()
    {
        // preset query only if hardcoded,
        // otherwise use whatever provided
        // by the user
        if (!empty($this->query)) {
            $this->data['query'] = $this->query;
        }

        // if password is empty, null it
        // so its not added to cmd during parsing
        if (empty($this->data['pass'])) {
            $this->data['pass'] = null;
        }
        return parent::run();
    }
}
