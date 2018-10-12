<?php
namespace App\Test\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Favicon Test
 */
class FaviconTest extends TestCase
{

    protected $folder;
    protected $file;

    protected function setUp(): void
    {
        $webroot = join(DIRECTORY_SEPARATOR, [__DIR__, '..', '..', 'webroot']) . DIRECTORY_SEPARATOR;
        $this->folder = $webroot;
        $this->file = 'favicon.ico';
    }

    /**
     * Check that the file exists
     *
     * @return void
     */
    public function testFaviconFileExists(): void
    {
        $this->assertFileExists($this->folder . $this->file);
    }
}
