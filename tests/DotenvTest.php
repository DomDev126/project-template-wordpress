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
		require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
		$this->assertTrue(class_exists('Dotenv'), 'Dotenv class is not loaded');
	}
}
