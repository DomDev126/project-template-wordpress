<?php
namespace Tests\Unit;

use \Dotenv;
/**
 * Dotenv Test
 * 
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class DotenvTest extends \PHPUnit_Framework_TestCase {

	protected $folder;
	protected $file;

	protected function setUp() {
		$this->folder = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
		$this->file = '.env.example';
	}

	/**
	 * Check that the file exists
	 */
	public function testDotenvExampleFileExists() {
		$this->assertTrue(file_exists($this->folder . $this->file), $this->file . " file does not exist in " . $this->folder);
	}

	/**
	* Check that we can parse the file
	*/
	public function testDotenvExampleFileIsParseable() {
		try {
			Dotenv::load($this->folder, $this->file);
		}
		catch (\Exception $e) {
			$this->fail("Failed to parse file " . $this->file . " in " . $this->folder . " : " . $e->getMessage());
		}
	}
}
