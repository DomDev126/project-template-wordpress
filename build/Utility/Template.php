<?php

/**
 * Template utilities collection class
 */
namespace Qobo\Utility;

class Template
{
    // Parse recursivly
    const FLAG_RECURSIVE = 0x1;

    // Replace missing tokens with empty value
    const FLAG_EMPTY_MISSING = 0x2;

    // Throw exception on missing tokens
    const FLAG_STRICT = 0x4;

    /**
     * Returns a list of unique tokens found in a given template
     *
     * @param string $template Template content
     * @param string $pre Token prefix
     * @param string $post Token postfix
     *
     * @return array List of tokens
     */
    public static function getTokens($template, $pre = '%%', $post = '%%')
    {
        $tokens = [];
        $regex = "/$pre(.*?)$post/";
        if (preg_match_all($regex, $template, $matches)) {
            $tokens = array_unique($matches[1]);
        }

        natsort($tokens);

        return $tokens;
    }

    /**
     * Parse template with given tokens
     *
     * @param string $template Template content
     * @param array $tokens List of key value tokens array
     * @param string $pre Token prefix
     * @param string $post Token postfix
     * @param int $flags Additianal flags for parsing
     *
     * @return string Parsed template
     */
    public static function parse($template, array $tokens, $pre = '%%', $post = '%%', $flags = self::FLAG_RECURSIVE | self::FLAG_STRICT)
    {
        // nothing to do with empty templates or when no tokens given
        if (empty($template) || empty($tokens)) {
            return $template;
        }

        // FLAG_EMPTY_MISSING and FLAG_STRICT are mutually exclusive
        if (($flags & self::FLAG_EMPTY_MISSING) &&  ($flags & self::FLAG_STRICT)) {
            throw new \InvalidArgumentException("Can't use FLAG_EMPTY_MISSING and FLAG_STRICT together");
        }

        // replace the tokens with their values
        $result = $template;
        foreach ($tokens as $token => $replacement) {
            $token = "$pre$token$post";
            $result = str_replace($token, $replacement, $result);
        }

        if ($flags & self::FLAG_RECURSIVE) {
            $recursiveResult = self::parse($result, $tokens, $pre, $post, $flags xor self::FLAG_RECURSIVE);
            if ($result <> $recursiveResult) {
                $result = self::parse($result, $pre, $post, $flags);
            }
        }

        // check for any tokens left in the result
        $remainingTokens = self::getTokens($result, $pre, $post);

        // ok if no tokens left
        if (empty($remainingTokens)) {
            return $result;
        }

        // throw exception if in strict mode
        if ($flags & self::FLAG_STRICT) {
            throw new \RuntimeException("Missing values for [" . implode(", ", $remainingTokens) . "] tokens");
        }

        // replace unknown tokens with empty string if FLAG_EMPTY_MISSING
        if ($flags & self::FLAG_EMPTY_MISSING) {

            $tokens = [];
            foreach ($remainingTokens as $token) {
                $tokens[$token] = "";
            }

            return self::parse($result, $tokens, $pre, $post);
        }


        return $result;
    }
}
