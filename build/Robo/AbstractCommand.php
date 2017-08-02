<?php
/**
 * Base command class for Qobo Robo.li
 *
 * @see http://robo.li/
 * @see https://qobo.biz/
 */
namespace Qobo\Robo;

use \Robo\Common\ConfigAwareTrait as configTrait;

abstract class AbstractCommand extends \Robo\Tasks
{
    use configTrait;

    /**
     * @var const $MSG_NO_DELETE Common message to decline delete action unless forces to
     */
    const MSG_NO_DELETE = "Won't delete anything unless you force me with '--force' option";

    /**
     * @var string $taskRegex Regex pattern to match method name and extract task collection
     *                        directory by first match and task class by second match
     */
    protected $taskRegex = '/^task([A-Z]+.*?)([A-Z]+.*)$/';

    /**
     * @var string $taskDir path to Tasks dir relative to our namespace
     */
    protected $taskDir = 'Task';

    /**
     * Magic __call that tries to find and execute a correct task based
     * on called method name that must start with 'task'
     *
     * @param string $method Method name that was called
     * @param array $args Arguments that were passed to the method
     *
     * @return 
     */
    public function __call($method, $args = null)
    {
        if (preg_match($this->taskRegex, $method, $matches)) {
            $className = __NAMESPACE__ . "\\" . $this->taskDir . "\\" . $matches[1] . "\\" . $matches[2];
            if (!class_exists($className)) {
                throw new \RuntimeException("Failed to find class '$className' for '$method' task");
            }
            return $this->task($className, $args);
        }
        throw new \RuntimeException("Called to undefined method '$method' of '" . get_called_class() . "'");
    }
}
