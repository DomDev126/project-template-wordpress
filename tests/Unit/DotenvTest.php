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
     *
     * @return mixed[]
     */
    public function dotEnvFilesProvider(): array
    {
        $root = join(DIRECTORY_SEPARATOR, [__DIR__, '..', '..']) . DIRECTORY_SEPARATOR;

        return [
            [$root, '.env.example'],
            [$root, '.env'],
        ];
    }

    /**
     * Check that the file exists
     *
     * @dataProvider dotEnvFilesProvider
     */
    public function testDotenvExampleFileExists(string $folder, string $file): void
    {
        $this->assertFileExists($folder . $file);
    }

    /**
     * Check that we can parse the file
     *
     * @dataProvider dotEnvFilesProvider
     */
    public function testDotenvExampleFileIsParseable(string $folder, string $file): void
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
