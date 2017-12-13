<?php

namespace Qobo\Robo\Task\File;

use Robo\Result;

/**
 * Change file/dir group ownership
 *
 * ```php
 * <?php
 * $this->taskFileChgrp()
 * ->path(['somefile.txt', './somedir'])
 * ->group('nginx')
 * ->recursive(true)
 * ->run();
 *
 * ?>
 * ```
 */
class Chgrp extends \Qobo\Robo\AbstractTask
{
    /**
     * {@inheritdoc}
     */
    protected $data = [
		'path'  => [],
		'group' => null,
		'recursive'	=> false,
	];

	/**
	 * {@inheritdoc}
	 */
	protected $requiredData = [
		'path',
		'group'
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
			$this->printInfo("Changing user group ownership on {path} to {group}", ['path' => $path, 'group' => $this->data['group']]);
			$result = static::chgrp($path, $this->data['group'], $this->data['recursive']);
		}

		if ($result) {
	        return Result::success($this, "Successfully changed path's group ownership", $this->data);
		}
		return Result::error($this, "Failed to change path's group ownership");
	}

	public static function chgrp($path, $group, $recursive)
	{
		$path = realpath($path);

		$result = chgrp($path, $group);

		if (!$result || is_file($path) || !$recursive) {
			return $result;
		}

		$paths = glob("$path/*");
		foreach ($paths as $path) {
			$result = static::chgrp($path, $group, true);
			if (!$result) {
				return $result;
			}

		}
		return $result;
	}
}
