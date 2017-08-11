<?php

/**
 * File utilities collection class
 */
namespace Qobo\Utility;

class File
{
    /**
     * Read file content into array of lines without trailing newlines
     *
     * @param string $path Path to file
     * @param bool $skipEmpty Flag to skip empty lines
     *
     * @return array Lines of file content
     */
    public static function readLines($path, $skipEmpty = false)
    {
        if (!is_file($path) || !is_readable($path)) {
            throw new \RuntimeException("File '$path' doesn't exist or is not a readable file");
        }


        $lines = ($skipEmpty)
            ? file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
            : file($path, FILE_IGNORE_NEW_LINES);

        if ($lines === false) {
            throw new \RuntimeException("Something went wrong while reading '$path' file content");
        }

        return $lines;
    }
    /**
     * Read file content into a string
     *
     * @param string $path Path to file
     * @param bool $skipEmpty Flag to skip empty lines
     *
     * @return string File content
     */
    public static function read($path, $skipEmpty = false)
    {
        return implode("\n", static::readLines($path, $skipEmpty));
    }

     /**
     * Write array of lines into file
     *
     * @param string $path Path to file
     * @param array $lines Content array
     *
     * @return bool true on success
     */
    public static function writeLines($path, $lines)
    {
        if (is_file($path) && !is_writable($path)) {
            throw new \RuntimeException("File '$path' is not a writable file");
        }

        // make sure every line has only one newline at the end
        $lines = array_map(function ($line) { return rtrim($line); }, $lines);

        $bytes = file_put_contents($path, implode("\n", $lines));
        if ($bytes === false) {
            throw new \RuntimeException("Something went wrong while writing content to '$path'");
        }

        return true;
    }

    /**
     * Write content to file
     *
     * @param string $path Path to file
     * @param string $content Content
     *
     * @return bool true on success
     */
    public static function write($path, $content)
    {
        return static::writeLines($path, explode("\n", $content));
    }
}
