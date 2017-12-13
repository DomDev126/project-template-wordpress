<?php

namespace Qobo\Robo\Task\File;

use Robo\Result;
use \Symfony\Component\Filesystem\Filesystem;

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
	 * Symfony Filesystem
	 */
	protected static $fs = null;

    /**
     * {@inheritdoc}
     */
    protected $data = [
		'path'  => [],
		'file_mode' => 0664,
		'dir_mode' => 0775,
		'recursive'	=> false,
	];

	/**
	 * {@inheritdoc}
	 */
	protected $requiredData = [
		'path',
		'fileMode',
		'dirMode'
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
			$this->printInfo("Changing mode on {path}", ['path' => $path]);
			$result = static::chmod($path, $this->data['file_mode'], $this->data['dir_mode'], $this->data['recursive']);
		}

		if ($result) {
	        return Result::success($this, "Successfully changed path's mode", $this->data);
		}

		return Result::error($this, "Failed to change path's mode");
	}

	public static function chmod($path, $fileMode, $dirMode, $recursive)
	{
		if (is_null(static::$fs)) {
			static::$fs = new Filesystem;
		}
		$path = realpath($path);

		try {

			if (is_file($path)) {
				static::$fs->chmod($path, $fileMode);
				return true;
			}

			static::$fs->chmod($path, $dirMode);
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
}
