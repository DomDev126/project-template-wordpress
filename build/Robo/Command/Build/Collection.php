<?php

namespace Qobo\Robo\Command\Build;

use \Qobo\Robo\AbstractCommand;
use \Qobo\Robo\DataAwareTrait;

class Collection extends AbstractCommand
{
    use DataAwareTrait;

    /**
     * {@inheritdoc}
     */
    protected $data = [];

    /**
     * {@inheritdoc}
     */
    protected $requiredData = [
        'cmd',
        'path'
    ];

    /**
     * @var array $commands List of all possible build commands
     */
    protected $commands = [
        'all' => [],
        'phpunit' => [
            'cmd'   => './vendor/bin/phpunit',
            'path'  => ['./tests'],
            'batch' => true,
            'out'  => 'build/coverage',
            'logs'  => 'build/logs'
        ],
        'phpcs' => [
            'cmd'   => './vendor/bin/phpcs',
            'path'  => ['./tests', './src'],
            'batch' => true
        ],
        'pdepend' => [
            'cmd'   =>'./vendor/bin/pdepend --jdepend-xml=%%LOGS%%/jdepend.xml --jdepend-chart=%%OUT%%/dependecies.svg --overview-pyramid=%%OUT%%/overview-pyramid.svg %%PATH%%',
            'path'  => ['./src'],
            'batch' => false,
            'out'  => 'build/pdepend',
            'logs'  => 'build/logs'
        ],
        'phploc' => [
            'cmd'   =>  './vendor/bin/phploc --count-tests --log-csv %%LOGS%%/phploc.csv --log-xml %%LOGS%%/phploc.xml %%PATH%%',
            'path'  => ['./src', './tests'],
            'batch' => true,
            'logs'  => 'build/logs'
        ],
        'phpmd' => [
            'cmd'   => './vendor/bin/phpmd %%PATH%% text codesize,controversial,naming,unusedcode',
            'path'  => ['./src'],
            'batch' => false
        ],
        'phpmd-ci' => [
            'cmd'   => './vendor/bin/phpmd %%PATH%% xml codesize,controversial,naming,unusedcode --reportfile %%LOGS%%/phpmd.xml',
            'path'  => ['./src'],
            'batch' => false,
            'logs'  => 'build/logs'
        ],
        'phpcpd' => [
            'cmd'   => './vendor/bin/phpcpd --log-pmd=%%LOGS%%/phpcpd.xml %%PATH%%',
            'path'  => ['./src'],
            'batch' => false,
            'logs'  => 'build/logs'
        ]
    ];

    /**
     * @var array $dirKeys keys from command data that relates to build output and log dirs
     */
    protected $dirKeys = ['out','logs'];

    /**
     * @var string $commandKey Key of the current command
     */
    protected $commandKey = null;

    /**
     * Run all build commands
     *
     * @param string $path Path to use for all commands
     */
    public function buildAll($path = null)
    {
        $this->commandKey = "all";

        // iterate over all available commands
        foreach ($this->commands as $command => $data) {

            // skip commands with empty paths
            if (!isset($data['path']) || empty($data['path'])) {
                continue;
            }

            // check all paths
            for ($idx = 0; $idx < count($data['path']); $idx++) {

                // remove the path from list if not valid
                try {
                    $this->checkPath($data['path'][$idx]);
                } catch (\Exception $e) {
                    unset($data['path'][$idx]);
                }
            }

            // nothing to do if no paths left for this command
            if (!count($data['path'])) {
                continue;
            }

            $this->data = $data;
            if ($path) {
                $this->data['path'] = $path;
            }
            $this->runCmd();
        }
    }

    /**
     * Clean build environment
     */
    public function buildClean()
    {
        $dirs = [];
        foreach ($this->commands as $cmd) {
            foreach ($this->dirKeys as $key) {
                if (isset($cmd[$key]) && !empty($cmd[$key])) {
                    $dirs []= $cmd[$key];
                }
            }
        }
        $dirs = array_unique($dirs);

        return $this->taskFilesystemStack()
            ->remove($dirs)
            ->mkdir($dirs)
            ->run();
    }

    /**
     * Run command
     */
    protected function runCmd($cmd = null, $path = null, $stopOnFail = true)
    {
        // check all ok
        if (!$this->initCmd($cmd, $path)) {
            return false;
        }

        $tokens = [];
        foreach ($this->dirKeys as $key) {
            if (!isset($this->data[$key])) {
                continue;
            }
            $tokens[strtoupper($key)] = $this->data[$key];
        }

        // run all together in batch mode
        if (isset($this->data['batch']) && $this->data['batch']) {

            $tokens['PATH'] = implode(" ", $this->data['path']);

            // parse command
            $cmd = $this->taskTemplateFileParse()
                ->tokens($tokens)
                ->parse($this->data['cmd']);

            $result = $this->taskExec($cmd)->run();
            return $result->wasSuccessful();
        }

        // run one by one for each path in non-batch mode
        foreach ($this->data['path'] as $path) {

            $tokens['PATH'] = $path;

            // parse command
            $cmd = $this->taskTemplateFileParse()
                ->tokens($tokens)
                ->parse($this->data['cmd']);

            $result = $this->taskExec($cmd)->run();

            // return false on any errors unless stopOnFail is false
            if (!$result->wasSuccessful() && $stopOnFail) {
                return false;
            }
        }

        // all should be fine by this time
        return true;
    }

    /**
     * Init command
     */
    protected function initCmd($cmd = null, $path = null)
    {
        // make sure we have commandKey set and valid
        if (!$this->commandKey || !isset($this->commands[$this->commandKey])) {
            throw new \RuntimeException("Command's commandKey is invalid");
        }

        // set all arguments and fix formats
        $this->fixData($cmd, $path);

        // merge default data
        $this->data = array_merge($this->commands[$this->commandKey], $this->data);

        try {
            $this->checkRequiredData();
            $this->checkCmd($cmd);

            foreach ($this->data['path'] as $path) {
                $this->checkPath($path);
            }
        } catch (\Exception $e) {
            $this->say("ERROR: " . $e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * Check if cmd exists and is an executable file
     */
    protected function checkCmd($cmd = null)
    {
        $cmdParts = preg_split("/\s+/", $this->data['cmd']);
        $cmd = $cmdParts[0];

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

    /**
     * Check if path exists and is a readable directory
     */
    protected function checkPath($path = null)
    {
        if (!is_string($path)) {
            throw new \InvalidArgumentException(sprintf("String argument expected, got '%s' instead", gettype($path)));
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
     * Helper function that properly assignes args to data
     */
    protected function fixData($cmd = null, $path = null)
    {
        // assign command if given
        if ($cmd) {
            $this->data['cmd'] = $cmd;
        }

        // assign path if given
        if ($path) {
            $this->data['path'] = $path;
        }

        // make sure path is always an array
        if (isset($this->data['path']) && !is_array($this->data['path'])) {
            $this->data['path'] = [ $this->data['path'] ];
        }
    }
}
