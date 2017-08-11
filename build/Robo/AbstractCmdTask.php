<?php

namespace Qobo\Robo;

use \Robo\Result;
use \Robo\Exception\TaskException;
use \Qobo\Utility\Template;

/**
 * Qobo base command task.
 */
abstract class AbstractCmdTask extends AbstractTask
{
    /**
     * {@inheritdoc}
     */
    protected $data = [
        // command to run
        'cmd'   => null,

        // array of paths agains which to run a command. If empty, command will not run (use './')
        'path'  => [],

        // whether to run a command agains each path separatly or in batch (path will be joined with ' ')
        'batch' => false,

        // path for any logs to be written to
        'logs'  => null,

        // path for any output files to be written to
        'out'   => null,
    ];

    /**
     * {@inhericdoc}
     */
    protected $requiredData = [
        'cmd',
        'path',
        'batch'
    ];

    /**
     * @var array $tokenKeys data keys to use as tokens in cmd
     */
    protected $tokenKeys = [
        'path',
        'out',
        'logs'
    ];

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $result = parent::run();
        if (!$result->wasSuccessful()) {
            return $result;
        }

		if (!is_array($this->data['path'])) {
			$this->data['path'] = [ $this->data['path'] ];
		}
        // validate our cmd and paths
        try {
            static::checkCmd($this->data['cmd']);
            foreach ($this->data['path'] as $path) {
                static::checkPath($path);
            }
        } catch (\Exception $e) {
            return Result::fromException($this, $e);
        }

        // get a list of commands to run
        $cmds = $this->getCommands();

        $this->data['data'] = [];
        foreach ($cmds as $cmd) {
            $this->printInfo("Running {cmd}", ['cmd' => $cmd]);
            $data = $this->runCmd($cmd);
            $this->data['data'] []= $data;

            // POSIX commands will exit with 1 on success
            // and 0 on failure
            if (!$data['status'] && $this->stopOnFail) {
                return Result::error($this, "Last command failed to run", $this->data);
            }

        }
        return Result::success($this, "Commands run successfully", $this->data);
    }

    /**
     * Check if path is readable
     */
    public static function checkPath($path)
    {
        if (!is_string($path)) {
            throw new \InvalidArgumentException(sprintf("String expected as path, got '%s' instead", gettype($path)));
        }
        if (!file_exists($path)) {
            throw new \InvalidArgumentException(sprintf("'%s' does not exist", $path));
        }

        if (!is_dir($path)) {
            throw new \InvalidArgumentException(sprintf("'%s' is not a directory", $path));
        }

        if (!is_readable($path)) {
            throw new \InvalidArgumentException(sprintf("'%s' is not readable", $path));
        }

        return true;
    }

    /**
     * Check if cmd exists and is an executable file
     */
    public static function checkCmd($cmd)
    {
        // cut out the actual executable part only
        // and leave args away
        $cmdParts = preg_split("/\s+/", $cmd);
        $cmd = $cmdParts[0];

        // try to find a command if not absolute path is given
        if (!preg_match('/^\.?\/.*$/', $cmd)) {
            $retval = null;
            $ouput = [];
            $fullCmd = exec("which $cmd", $output, $retval);
            if ($retval) {
                throw new \InvalidArgumentException(sprintf("Failed to find full path for '%s'", $cmd));
            }
            $cmd = trim($fullCmd);
        }

        if (!file_exists($cmd)) {
            throw new \InvalidArgumentException(sprintf("'%s' does not exist", $cmd));
        }

        if (!is_file($cmd)) {
            throw new \InvalidArgumentException(sprintf("'%s' is not a file", $cmd));
        }
        if (!is_executable($cmd)) {
            throw new \InvalidArgumentException(sprintf("'%s' is not executable", $cmd));
        }

        return true;
    }

    protected function runCmd($cmd)
    {
        $data = [
            'message'   => null,
            'output'    => [],
            'status'    => null
        ];
        $data['message'] = exec($cmd, $data['output'], $data['status']);

        return $data;
    }

    /**
     * Get commands
     */
    public function getCommands()
    {
        // generate basic tokens
        $tokens = [];
        foreach ($this->tokenKeys as $key) {

            // skip if don't have data for the token available
            if (!isset($this->data[$key])) {
                continue;
            }

            $tokens[strtoupper($key)] = $this->data[$key];
        }

        // return a combined command for all paths if in batch mode
        if ($this->data['batch']) {
            $tokens['PATH'] = implode(" ", $this->data['path']);
            return [ Template::parse($this->data['cmd'], $tokens, '%%', '%%') ];
        }

        // get an array of standalone commands in non-batch mode
        $cmds = [];
        foreach ($this->data['path'] as $path) {
            $tokens['PATH'] = $path;
            $cmds []= Template::parse($this->data['cmd'], $tokens, '%%', '%%');
        }

        return $cmds;
    }
}
