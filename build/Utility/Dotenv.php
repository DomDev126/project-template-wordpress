<?php

/**
 * Dotenv utilities collection class
 */
namespace Qobo\Utility;

use Qobo\Utility\File;

class Dotenv
{
    // Skip variable with empty values
    const FLAG_SKIP_EMPTY = 0x1;

    // Replace duplicates
    const FLAG_REPLACE_DUPLICATES = 0x2;

    // Throw exception on duplicates
    const FLAG_STRICT = 0x4;

    /**
     * For compatibility with vlucas/dotenv
     */
    public static function load($path = __DIR__, $file = ".env")
    {
        $content = File::readLines($path . "/" . $file);
        $dotenv = static::parse($content);
        return static::apply($dotenv);
    }

    /**
     * Parse dotenv content
     *
     * @param string|array string $dotenv Dotenv content
     * @param array $data Any preset env variables
     * @param int $flags Additianal flags for parsing
     *
     * @return array List of dotenv variables with their values
     */
    public static function parse($dotenv, $data = [], $flags = self::FLAG_STRICT)
    {
        if (!is_array($dotenv)) {
            $dotenv = explode("\n", $dotenv);
        }
        $result = (!empty($data)) ? $data : [];

        // nothing to do with empty dotenv
        if (empty($dotenv)) {
            return $result;
        }

        // FLAG_REPLACE_DUPLICATES and FLAG_STRICT are mutually exclusive
        if (($flags & static::FLAG_REPLACE_DUPLICATES) &&  ($flags & static::FLAG_STRICT)) {
            throw new \InvalidArgumentException("Can't use FLAG_REPLACE_DUPLICATES and FLAG_STRICT together");
        }

        foreach ($dotenv as $line) {
            $line = trim($line);

            // Disregard comments
            if (strpos($line, '#') === 0) {
                continue;
            }

            // Only use nont-empty lines that look like setters
            if (!preg_match('#^\s*(.+?)=(.*)?$#', $line, $matches)) {
                continue;
            }

            $name = static::normalizeName($matches[1]);

            $value = static::normalizeValue(trim($matches[2]));
            $value = static::resolveNested($value, $result);

            if ($value === "" && ($flags & static::FLAG_SKIP_EMPTY)) {
                continue;
            }

            if (!isset($result[$name]) || ($flags & static::FLAG_REPLACE_DUPLICATES)) {
                $result[$name] = $value;
                continue;
            }

            if ($flags & static::FLAG_STRICT) {
                throw new \RuntimeException("Duplicate value found for variable '$name'");
            }
        }

        return $result;
    }

    public static function apply($dotenv, $data = [], $flags = self::FLAG_STRICT)
    {
        if (!is_array($dotenv)) {
            $dotenv = static::parse($dotenv, $data, $flags);
        }

        foreach ($dotenv as $name => $value) {
            if (static::findEnv($name) && ($flags & static::FLAG_STRICT)) {
                throw new \RuntimeException("Environment variable '$name' already present");
            }
            static::setEnv($name, $value);
        }

        return $dotenv;
    }

    protected static function normalizeName($name)
    {
        return trim(str_replace(['export ', '\'', '"'], '', $name));
    }

    protected static function normalizeValue($value)
    {
        $value = trim($value);
        if (!$value) {
            return '';
        }
        if (strpbrk($value[0], '"\'') !== false) { // value starts with a quote
            $quote = $value[0];
            $regexPattern = sprintf('/^
                %1$s          # match a quote at the start of the value
                (             # capturing sub-pattern used
                 (?:          # we do not need to capture this
                  [^%1$s\\\\] # any character other than a quote or backslash
                  |\\\\\\\\   # or two backslashes together
                  |\\\\%1$s   # or an escaped quote e.g \"
                 )*           # as many characters that match the previous rules
                )             # end of the capturing sub-pattern
                %1$s          # and the closing quote
                .*$           # and discard any string after the closing quote
                /mx', $quote);
            $value = preg_replace($regexPattern, '$1', $value);
            $value = str_replace("\\$quote", $quote, $value);
            $value = str_replace('\\\\', '\\', $value);
        } else {
            $parts = explode(' #', $value, 2);
            $value = $parts[0];
        }

        return trim($value);
    }

    protected static function resolveNested($value, $env)
    {
        if (strpos($value, '$') === false) {
            return $value;
        }

        return preg_replace_callback(
            '/{\$([a-zA-Z0-9_]+)}/',
            function ($items) {
                $nested = array_key_exists($items[1], $env)
                    ? $env[$items[1]]
                    : null;
                return (is_null($nested))
                    ? $items[0]
                    : $nested;
            },
            $value
        );
    }

    protected static function findEnv($name)
    {
        if (array_key_exists($name, $_ENV)) {
            return $_ENV[$name];
        }

        if (array_key_exists($name, $_SERVER)) {
            return $_SERVER[$name];
        }

        $value = getenv($name);
        return $value === false ? null : $value;

    }

    protected static function setEnv($name, $value)
    {
        putenv("$name=$value");
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }
}
