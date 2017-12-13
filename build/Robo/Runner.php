<?php

namespace Qobo\Robo;

use \Robo\Runner as RoboRunner;

/**
 * Robo Runner to allow custom error handler
 */
class Runner extends RoboRunner
{
    private $lastErrno = null;

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

        $this->lastErrno = $errno;

        // throw the exception
        throw new \RuntimeException($msg, $errno);
    }

    public function installRoboHandlers()
    {
        register_shutdown_function(array($this, 'shutdown'));

        if (PHP_MAJOR_VERSION < 7) {
            set_error_handler(array($this, 'handleError'), E_ALL & ~E_STRICT);
        } else {
            set_error_handler(array($this, 'handleError'), E_ALL);
        }
    }

    public function shutdown()
    {
        exit($this->lastErrno);
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

