<?php

namespace Qobo\Robo;

use \Robo\Common\ConfigAwareTrait;
use \Robo\Task\BaseTask;
use \Robo\Result;

/**
 * Qobo base task.
 */
abstract class AbstractTask extends BaseTask
{
    use DataAwareTrait;
    use ConfigAwareTrait;

    /**
     * @var array $data Task data fields
     */
    protected $data = [];

    /**
     * @var array $requiredData List of required data fields keys
     */
    protected $requiredData = [];

    /**
     * @var bool $stopOnFail Flag on whether to stop on any fails
     */
    protected $stopOnFail = false;

    /**
     * @var string $configPrefix Config path prefix
     */
    protected static $configPrefix = "task.";

    /**
     * @var string $configClassRegexPattern Regex to extract class name for config
     */
    protected static $configClassRegexPattern = "/^.*Tasks?\.(.*)\.[^\.]+$/";

    /**
     * @var string $configClassRegexReplacement Regex match to use as extracted class name for config
     */
    protected static $configClassRegexReplacement = '${1}';

    public function __construct($params)
    {
    }

    /**
     * Magic data setter
     */
    public function __call($name, $value)
    {
        return $this->setData($name, $value);
    }


    /**
     * {inheritdoc}
     */
    public function run()
    {
        // for any key defind in data
        // set it to config value, if available
        foreach ($this->data as $k => $v) {
            $default = $this->getConfigValue($k);

            // specifically check for null to avoid problems with false and 0
            // being overwriten
            if ($this->data[$k] === null && $default !== null) {
                $this->data[$k] = $default;
                continue;
            }
            // if key value is an array, merge the config value to it
            if (is_array($this->data[$k]) && is_array($default)) {
                $this->data[$k] = array_merge_recursive($this->data[$k], $default);
            }
            continue;
        }

        // check if we have all required data fields
        try {
            $this->checkRequiredData();
        } catch (\Exception $e) {
            return Result::fromException($this, $e);
        }

        // general success, as will be overriden by child classes
        return Result::success($this, "Task completed successfully", $this->data);
    }

    /**
     * Set stopOnFail config
     */
    public function stopOnFail($stopOnFail = true)
    {
        $this->stopOnFail = $stopOnFail;
        return $this;
    }

    /**
     * Override of Robo\Common\ConfigAwareTrait configPrefix()
     */
    protected static function configPrefix()
    {
        return static::$configPrefix;
    }

    /**
     * Override of Robo\Common\ConfigAwareTrait configClassIdentifier($classname)
     */
    protected static function configClassIdentifier($classname)
    {
        return preg_replace(
            static::$configClassRegexPattern,
            static::$configClassRegexReplacement,
            str_replace(
                '\\',
                '.',
                $classname
            )
        );
    }

    /**
     * Override of Robo\Common\ConfigAwareTrait getClassKey()
     *
     * makes method protected instead of private
     */
    protected static function getClassKey($key)
    {
        return sprintf(
            "%s%s.%s",
            static::configPrefix(),
            static::configClassIdentifier(get_called_class()),
            $key
        );
    }

    /**
     * A quick fix on printInfo, as it is not very friendly
     * when you use 'name' placeholders or even just have 'name'
     * set in the data
     */
    protected function printInfo($msg, $data = null)
    {
        // pass-through when no 'name' found in data
        if ($data == null || !isset($data['name'])) {
            return $this->printTaskInfo($msg, $data);
        }

        // doubt someone will use this ever in data
        $key = 'print_task_info_name_replacement_macro';

        // replace 'name' with above key both in data
        // and in msg placeholders
        $data[$key] = $data['name'];
        unset($data['name']);
        $msg = str_replace('{name}','{' . $key . '}', $msg);

        // print nice message
        $result = $this->printTaskInfo($msg, $data);

        return $result;
    }
}
