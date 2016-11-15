<?php
namespace Tests\Unit;

/**
 * Composer Test
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class ComposerTest extends \PHPUnit_Framework_TestCase
{

    const COMPOSER_JSON = 'composer.json';
    const COMPOSER_LOCK = 'composer.lock';

    protected $folder;

    protected function setUp()
    {
        $this->folder = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
    }

    public function getComposerFiles()
    {
        return [
            [self::COMPOSER_JSON],
            [self::COMPOSER_LOCK],
        ];
    }

    /**
     * @dataProvider getComposerFiles
     */
    public function testComposerFiles($file)
    {
        $this->assertFileExists($this->folder . $file, $file . " file is missing");
        $this->assertTrue(is_readable($this->folder . $file), $file . " file is not readable");

        $content = file_get_contents($this->folder . $file);
        $this->assertGreaterThan(0, strlen($content), $file . " file is empty");

        // This is useful for catching merge conflicts, for example
        $json = json_decode($content);
        $this->assertNotNull($json, "Failed to parse JSON in file " . $file);

        $this->assertNotEmpty($json, "Empty result from JSON parsing in file " . $file);
    }

    public function testComposerLockUpToDate()
    {
        # Thanks to: http://stackoverflow.com/a/28730898
        $lock = json_decode(file_get_contents($this->folder . self::COMPOSER_LOCK))->{'hash'};
        $json = md5(file_get_contents($this->folder . self::COMPOSER_JSON));

        $this->assertEquals($lock, $json, "composer.lock is outdated");
    }
}
