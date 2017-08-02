<?php

namespace Qobo\Robo;

use \Robo\Config;
use \Robo\Robo;

/**
 * Qobo Robo App
 *
 * ```php
 * $app = new \Qobo\Robo\App();
 * $statusCode = $app->name("My App")
 *     ->version("v100.500.0")
 *     ->args($_SERVER['argv'])
 *     ->config([])
 *     ->cmdPath('Command/')
 *     ->grpCmd(false)
 *     ->run();
 *
 * exit($statusCode);
 *
 * ```
 */
class App
{
    use DataAwareTrait;

    /**
     * @var array $data
     */
    protected $data = [
        'name'      => 'Robo Qobo App',
        'version'   => 'v1.0.0',
        'args'      => [],
        'config'    => [],
        'cmd_path'  => 'Command/',
        'grp_cmd'   => true
    ];

    /**
     * @var array $requiredData
     */
    protected $requiredData = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        // all data is required for the app to run
        $this->requiredData = array_keys($this->data);
    }

    /**
     * Magic data setter
     */
    public function __call($name, $value)
    {
        return $this->setData($name, $value);
    }

    /**
     * Run the app
     */
    public function run()
    {
        // discover available commands
        $commands = $this->getCommands();

        // set our config
        Robo::createDefaultContainer(
            null, 
            null, 
            null, 
            new Config($this->data['config'])
        );

        // run Robo, run...
        $statusCode = Robo::run(
            $this->data['args'],
            $commands,
            $this->data['name'],
            $this->data['version']
        );
    }

    /**
     * Get list of available command classes
     *
     * @return array List of command classes
     */
    protected function getCommands()
    {
        // construct command classes path depending on grp_cmd flag
        $cmdPath = rtrim(__DIR__ . "/" . $this->data['cmd_path'], '/') . '/';
        $cmdPath .= ($this->data['grp_cmd']) ? "*/*.php" : "*.php";

        // construct commad path regex depending on grp_cmd flag
        $cmdPattern = ($this->data['grp_cmd'])
            ? '/^.*\/([^\/]+)\/([^\/]+)\.php$/'
            : '/^.*\/([^\/]+)\.php$/';

        // find all command files and commands
        $commands = [];
        foreach (glob($cmdPath) as $file) {

           // match only php files, extract group dir name
            if (!preg_match($cmdPattern, $file, $matches)) {
                continue;
            }

            // construct a class name from our namespace, optional
            // command group subdir and actual class file name
            $className = __NAMESPACE__ . "\\" . str_replace("/","\\", $this->data['cmd_path']) . $matches[1];
            $className .= ($this->data['grp_cmd']) ? "\\" . $matches[2] : "";

            // skip if class doesn't exist
            if (!class_exists($className)) {
                continue;
            }

            // add to our commands list
            $commands []= $className;
        }

        return $commands;
    }
}
