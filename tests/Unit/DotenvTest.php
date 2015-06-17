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
	 * Check that .env.example file exists
	 */
	public function testDotenvExampleFileExists() {
		$this->assertTrue(file_exists($this->folder . $this->file), ".env.example file does not exist [" . $this->folder . $this->file . "]");
	}

	/**
	* Check that we can parse .env.example file
	*
	* @depends testDotenvExampleFileExists
	*/
	public function testDotenvExampleFileIsParseable() {
		Dotenv::load($this->folder, $this->file);
	}
}
