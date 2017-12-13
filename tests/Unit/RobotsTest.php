<?php
namespace Tests\Unit;

/**
 * Robots Test
 */
class RobotsTest extends \PHPUnit_Framework_TestCase
{

    protected $folder;
    protected $file;

    protected function setUp()
    {
        $webroot = join(DIRECTORY_SEPARATOR, [__DIR__, '..', '..', 'webroot']) . DIRECTORY_SEPARATOR;
        $this->folder = $webroot;
        $this->file = 'robots.txt';
    }

    /**
     * Check that the file exists
     */
    public function testRobotsFileExists()
    {
        $this->assertFileExists($this->folder . $this->file);
    }

    /**
     * Check that the file is not empty
     *
     * @depends testRobotsFileExists
     */
    public function testRobotsFileIsNotEmpty()
    {
        $content = trim(file_get_contents($this->folder . $this->file));
        $this->assertFalse(empty($content), $this->file . " file is empty in " . $this->folder);
    }
}
