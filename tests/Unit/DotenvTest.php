<?php
namespace App\Test\Unit;

use Dotenv;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Dotenv Test
 */
class DotenvTest extends TestCase
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
        } catch (Exception $e) {
            $this->fail("Failed to parse file " . $file . " in " . $folder . " : " . $e->getMessage());
        }
        // Check any variable just to make sure it is set correctly
        $result = getenv("DB_DUMP_PATH");
        $this->assertEquals("etc/mysql.sql", $result, "Environment variables are not set correctly");
    }
}
