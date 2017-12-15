<?php

namespace Qobo\Robo\Task\File;

use Robo\Result;

/**
 * Change file mode
 *
 * ```php
 * <?php
 * $this->taskFileChmod()
 * ->path(['somefile.txt', './somedir'])
 * ->fileMode(0644)
 * ->dirMode(0755)
 * ->recursive(true)
 * ->run();
 *
 * ?>
 * ```
 */
class Chmod extends \Qobo\Robo\AbstractTask
{

    /**
     * {@inheritdoc}
     */
    protected $data = [
		'path'  => [],
		'file_mode' => '0664',
		'dir_mode' => '0775',
		'recursive'	=> false,
	];

	/**
	 * {@inheritdoc}
	 */
	protected $requiredData = [
		'path',
		'file_mode',
		'dir_mode'
	];

    /**
     * {@inheritdoc}
     */
    public function run()
	{
		if (!is_array($this->data['path'])) {
			$this->data['path'] = [ $this->data['path'] ];
        }
        foreach ($this->data['path'] as $path) {
			$this->printInfo("Changing mode on {path} (dir: {dir_mode}, file: {file_mode})", ['path' => $path, 'dir_mode' => $this->data['dir_mode'], 'file_mode' => $this->data['file_mode']]);
			$result = static::chmod($path, $this->data['file_mode'], $this->data['dir_mode'], $this->data['recursive']);
		}

		if ($result) {
	        return Result::success($this, "Successfully changed path's mode", $this->data);
		}

		return Result::error($this, "Failed to change path's mode");
	}

	public static function chmod($path, $fileMode, $dirMode, $recursive)
    {
        $fileMode = static::valueToOct($fileMode);
        $dirMode = static::valueToOct($dirMode);

		$path = realpath($path);

		try {

			if (is_file($path)) {
                chmod($path, $fileMode);
				return true;
			}

            chmod($path, $dirMode);
		} catch (\Exception $e) {
			return false;
		}

		if (!$recursive) {
			return true;
		}

		$paths = glob("$path/*");
		foreach ($paths as $path) {
			if (!static::chmod($path, $fileMode, $dirMode, true)) {
				return false;
			}
		}
		return true;
    }

    protected static function valueToOct($value)
    {
        // If the value is a string in a form '0777', then extract octal value
        if (is_string($value) && (strpos($value, '0') === 0)) {
            $value = intval($value, 8);
        }
        // If the value is not octal, convert
        if (decoct(octdec($value)) <> $value) {
            return base_convert((string) $value, 10, 8);
        }
        return $value;
    }
}
