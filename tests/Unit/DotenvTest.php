<?php
namespace App\Test\Unit;

use InvalidArgumentException;
use josegonzalez\Dotenv\Loader;
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
            [$root . '.env.example'],
            [$root . '.env'],
        ];
    }

    /**
     * Check that the file exists
     *
     * @dataProvider dotEnvFilesProvider
     */
    public function testDotenvExampleFileExists(string $file): void
    {
        $this->assertFileExists($file);
    }

    /**
     * Check that we can parse the file
     *
     * @dataProvider dotEnvFilesProvider
     */
    public function testDotenvExampleFileIsParseable(string $file): void
    {
        try {
            // NOTE: in order to avoid logic exceptions caused by multilpe files, we do overwrite
            (new Loader($file))->parse()->toEnv(true)->putenv(true);
        } catch (InvalidArgumentException $e) {
            $this->fail("Failed to parse file [" . $file . "] : " . $e->getMessage());
        }
        // Check any variable just to make sure it is set correctly
        $result = getenv("DB_DUMP_PATH");
        $this->assertEquals("etc/mysql.sql", $result, "Failed to load environment variables from file [$file]");
    }
}
