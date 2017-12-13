<?php

namespace Qobo\Robo\Task\Build;

use \Robo\Result;

class All extends Base
{

    /**
     * Run all build commands
     */
    public function run()
    {
        $finalResult = [];

        // iterate over all available tasks
        foreach ($this->tasks as $task => $data) {

            // skip commands with empty paths
            if (!isset($data['path']) || empty($data['path'])) {
                continue;
            }

            // check all paths
            for ($idx = 0; $idx < count($data['path']); $idx++) {

                // remove the path from list if not valid
                try {
                    static::checkPath($data['path'][$idx]);
                } catch (\Exception $e) {
                    unset($data['path'][$idx]);
                }
            }

            // nothing to do if no paths left for this command
            if (!count($data['path'])) {
                continue;
            }

            $this->data = $data;
            $result = parent::run();

            array_push($finalResult, $result->getData());

            if (!$result->wasSuccessful() && $this->stopOnFail) {
                return Result::error($this, "Build command '" . $this->data['cmd'] . "' failed", $finalResult);
            }
        }

        return Result::success($this, "All build commands passed", $finalResult);
    }
}

