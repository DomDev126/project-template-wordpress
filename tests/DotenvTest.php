<?php
/**
 * Dotenv Test
 * 
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class DotenvTest extends PHPUnit_Framework_TestCase {

	/**
	 * Check that Dotenv is loaded by composer
	 */
	public function testDotenvClassLoaded() {
		$this->assertTrue(class_exists('Dotenv'), 'Dotenv class is not loaded');
	}

	/**
	 * Check that .env.example file exists
	 */
	public function testDotenvExampleFileExists() {
		$file = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '.env.example';
		$this->assertTrue(file_exists($file), ".env.example file does not exist [$file]");
	}

}
