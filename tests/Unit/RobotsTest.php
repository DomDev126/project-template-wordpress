<?php
namespace Tests\Unit;

/**
 * Robots Test
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class RobotsTest extends \PHPUnit_Framework_TestCase {

	protected $folder;
	protected $file;

	protected function setUp() {
		$this->folder = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
		$this->file = 'robots.txt';
	}

	/**
	 * Check that the file exists
	 */
	public function testRobotsFileExists() {
		$this->assertTrue(file_exists($this->folder . $this->file), $this->file . " file does not exist in " . $this->folder);
	}

	/**
	 * Check that the file is not empty
	 */
	public function testRobotsFileIsNotEmpty() {
		$content = trim(file_get_contents($this->folder . $this->file));
		$this->assertFalse(empty($content), $this->file . " file is empty in " . $this->folder);
	}

}
