<?php
namespace Tests\Unit;
/**
 * Dotenv Test
 * 
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class DotenvTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Check that .env.example file exists
	 */
	public function testDotenvExampleFileExists() {
		$file = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '.env.example';
		$this->assertTrue(file_exists($file), ".env.example file does not exist [$file]");
	}

}
