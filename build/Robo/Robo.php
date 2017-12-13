<?php

namespace Qobo\Robo;

use \Robo\Robo as RoboRobo;

/**
 * Extend Robo to overwrite run method and substitute our own Runner
 */
class Robo extends RoboRobo
{

    public static function run($argv, $commandClasses, $appName = null, $appVersion = null, $output = null, $repository = null)
    {
        // This line is the whole idea of the class
        $runner = new \Qobo\Robo\Runner($commandClasses);


        $runner->setSelfUpdateRepository($repository);
        $statusCode = $runner->execute($argv, $appName, $appVersion, $output);
        return $statusCode;
    }
}
