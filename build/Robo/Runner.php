<?php

namespace Qobo\Robo;

use \Robo\Runner as RoboRunner;

/**
 * Robo Runner to allow custom error handler
 */
class Runner extends RoboRunner
{
    /**
     * Custom error handler that will throw an exception on any errors
     */
    public function handleError()
    {
        // get error info
        list ($errno, $message, $file, $line) = func_get_args();

        // construct error message
        $msg = "ERROR ($errno): $message";
        if ($line !== null) {
            $file = "$file:$line";
        }

        if ($file !== null) {
            $msg .= " [$file]";
        }

        // throw the exception
        throw new \RuntimeException($msg, $errno);
    }

    /**
     * @param string $selfUpdateRepository
     *
     * Have to have this here as it is not properly inherited from parent somehow
     */
    public function setSelfUpdateRepository($selfUpdateRepository)
    {
        $this->selfUpdateRepository = $selfUpdateRepository;
    }
}

