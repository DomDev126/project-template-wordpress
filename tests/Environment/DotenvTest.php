<?php
namespace App\Test\Environment;

use PHPUnit\Framework\TestCase;

/**
 * Dotenv Test
 *
 * @group  environment
 */
class DotenvTest extends TestCase
{

    /**
     * Check that Dotenv is loaded by composer
     */
    public function testDotenvClassLoaded()
    {
        $this->assertTrue(class_exists('Dotenv'), 'Dotenv class is not loaded');
    }
}
