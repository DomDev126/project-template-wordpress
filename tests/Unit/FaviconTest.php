<?php
namespace Tests\Unit;

/**
 * Favicon Test
 * 
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class FaviconTest extends \PHPUnit_Framework_TestCase {

	protected $folder;
	protected $file;

	protected function setUp() {
		$this->folder = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
		$this->file = 'favicon.ico';
	}

	/**
	 * Check that the file exists
	 */
	public function testFaviconFileExists() {
		$this->assertTrue(file_exists($this->folder . $this->file), $this->file . " file does not exist in " . $this->folder);
	}

}
