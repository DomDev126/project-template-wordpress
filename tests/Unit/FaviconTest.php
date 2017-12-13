<?php
namespace Tests\Unit;

/**
 * Favicon Test
 */
class FaviconTest extends \PHPUnit_Framework_TestCase
{

    protected $folder;
    protected $file;

    protected function setUp()
    {
        $webroot = join(DIRECTORY_SEPARATOR, [__DIR__, '..', '..', 'webroot']) . DIRECTORY_SEPARATOR;
        $this->folder = $webroot;
        $this->file = 'favicon.ico';
    }

    /**
     * Check that the file exists
     */
    public function testFaviconFileExists()
    {
        $this->assertFileExists($this->folder . $this->file);
    }
}
