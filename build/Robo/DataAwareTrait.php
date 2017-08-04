<?php

namespace Qobo\Robo;

use \Robo\Result;

/**
 * Data aware trait
 */
trait DataAwareTrait
{
    /**
     * Data setter
     * Make sure only valid data passes through
     *
     * @param string $name data key name
     * @param mixed $value data value name
     */
    public function setData($name, $value)
    {
        if (!isset($this->data)) {
            throw new \RuntimeException("Data property is required for DataAwareTrait to work");
        }

        // we use snake_case field keys
        // but camelCase setters
        $name = $this->decamelize($name);

        // only set values for predefined data keys
        if (array_key_exists($name, $this->data)) {
            $this->data[$name] = $value[0];
        }

        return $this;
    }

    /**
     * Check that all required data present
     *
     * @return \Robo\Result
     */
    protected function checkRequiredData()
    {
        if (!isset($this->data)) {
            throw new \RuntimeException("'data' property is required for DataAwareTrait to work");
        }
        if (!isset($this->requiredData)) {
            throw new \RuntimeException("'requiredData' property is required for DataAwareTrait to work");
        }

        $missing = [];
        foreach ($this->requiredData as $key) {
            if (!isset($this->data[$key]) or empty($this->data[$key])) {
                $missing []= $key;
            }
        }

        if (!count($missing)) {
            return true;
        }

        throw new \RuntimeException(
            sprintf("Missing required data field(s) [%s]", implode(",", array_map([$this,"camelize"], $missing)))
        );
    }


    /**
     * Ported from Ruby's String#decamelize
     *
     * @param string $word String to convert
     * @return string
     */
    protected function decamelize($word)
    {
        return preg_replace_callback(
            '/(^|[a-z])([A-Z])/',
            function ($matches) {
                return strtolower(strlen($matches[1]) ? $matches[1] . "_" . $matches[2] : $matches[2]);
            },
            $word
        );
    }

    /**
     * Ported from Ruby's String#camelize
     *
     * @param string $word String to convert
     * @return string
     */
    protected function camelize($word)
    {
        return preg_replace_callback(
            '/(^|[a-z])([A-Z])/',
            function ($matches) {
                return strtoupper($matches[2]);
            },
            $word
        );
    }
}
