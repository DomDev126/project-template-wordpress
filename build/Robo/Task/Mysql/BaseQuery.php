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
        'cmd'   => 'mysql %%ARGS%% -e %%QUERY%%',
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
        'query',
        'db'
    ];

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $tokens = [
            'QUERY' => escapeshellarg((string) $this->data['query']),
            'ARGS'  => preg_replace('/\s+/',' ',implode(" ", [
                (!empty($this->data['host']) ? '-h ' . escapeshellarg($this->data['host']) : ''),
                (!empty($this->data['port']) ? '-P ' . escapeshellarg($this->data['port']) : ''),
                (!empty($this->data['user']) ? '-u ' . escapeshellarg($this->data['user']) : ''),
                (!empty($this->data['pass']) ? '-p' . escapeshellarg($this->data['pass']) : ''),
                (!empty($this->data['db']) ? escapeshellarg($this->data['db']) : '')
            ]))
        ];
        $cmd = Template::parse($this->data['cmd'], $tokens);

        try {
            $this->checkCmd($cmd);
        } catch (\Exception $e) {
            return Result::fromException($this, $e);
        }

        $data = $this->runCmd($cmd);
        $this->data['data'] = $data;

        // mysql exit codes is not POSIX and it will return 0 on success and 1 on failure
        if ($data['status']) {
            return Result::error($this, "Last command failed to run", $this->data);
        }
        return Result::success($this, "Successfully retrieved repo hash", $this->data);
    }


}
