<?php

namespace Qobo\Robo\Task\Build;

use \Robo\Result;
use Symfony\Component\Filesystem\Filesystem as Filesystem;

class Clean extends Base
{
    /**
     * Clean build environment
     */
    public function run()
    {
        $dirs = [];
        foreach ($this->tasks as $task) {
            foreach ($this->dirKeys as $key) {
                if (isset($task[$key]) && !empty($task[$key])) {
                    $dirs []= $task[$key];
                }
            }
        }
        $dirs = array_unique($dirs);

        foreach ($dirs as $dir) {
            if (preg_match('/^\/[^\/]*$/', $dir)) {
                return Result::error($this, "Attempt to delete system dir '$dir'");
                die;
            }
        }

        $fs = new Filesystem();
        try {
            $fs->remove($dirs);
            $fs->mkdir($dirs);
        } catch (\Exception $e) {
            return Result::fromException($this, $e);
        }

        return Result::success($this, "All cleaned up", ['data' => $dirs]);
    }


}

