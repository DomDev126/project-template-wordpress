<?php
namespace Tests\Unit;

use \Dotenv;

/**
 * Dotenv Test
 */
class DotenvTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Provide .env file locations
     */
    public function dotEnvFilesProvider()
    {
        $root = join(DIRECTORY_SEPARATOR, [__DIR__, '..', '..']) . DIRECTORY_SEPARATOR;

        return [
         '.env.example' => [$root, '.env.example'],
         '.env' => [$root, '.env'],
        ];
    }

    /**
     * Check that the file exists
     *
     * @dataProvider dotEnvFilesProvider
     */
    public function testDotenvExampleFileExists($folder, $file)
    {
        $this->assertFileExists($folder . $file);
    }

    /**
     * Check that we can parse the file
     *
     * @dataProvider dotEnvFilesProvider
     */
    public function testDotenvExampleFileIsParseable($folder, $file)
    {
        try {
            Dotenv::load($folder, $file);
        } catch (\Exception $e) {
            $this->fail("Failed to parse file " . $file . " in " . $folder . " : " . $e->getMessage());
        }
    }
}
