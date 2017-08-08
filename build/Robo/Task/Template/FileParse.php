<?php

namespace Qobo\Robo\Task\Template;

use Robo\Result;

/**
 * Parse template file
 *
 * ```php
 * <?php
 * $this->taskTemplateFileParse()
 * ->path('template.ctp')
 * ->wrap('###')
 * ->tokens(['key1' => 'value1', 'key2' => 'value2'])
 * ->recurse(false)
 * ->run();
 *
 *<?php
 * $this->taskTemplateFileParse()
 * ->path('template.ctp')
 * ->pre('{{')
 * ->post('}}')
 * ->tokens(['key1' => 'value1', 'key2' => 'value2'])
 * ->recurse(true)
 * ->run();
 *
 * ?>
 * ```
 */
class FileParse extends \Qobo\Robo\AbstractTask
{
    /**
     * {@inheritdoc}
     */
    protected $data = [
        'path'          => null,
        'pre'           => '%%',
        'post'          => '%%',
        'tokens'        => [],
        'recurse'       => true
    ];

    /**
     * {@inheritdoc}
     */
    protected $requiredData = [
        'path',
        'pre',
        'post'
    ];

    /**
     * Shortcut for setting same pre and post
     *
     * @param string $str Prefix and postfix for template tocken
     *
     * @return $this
     */
    public function wrap($str)
    {
        $this->data['pre'] = $str;
        $this->data['post'] = $str;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $result = parent::run();
        if (!$result->wasSuccessful()) {
            return $result;
        }

        $this->printInfo("Parsing {path} template", $this->data);
        if (!is_file($this->data['path']) || !is_readable($this->data['path'])) {
            return Result::error($this, "Template does not exist or is not readable", $this->data);
        }

        $file = file_get_contents($this->data['path']);
        $this->data['data'] = $this->parse($file);

        return Result::success($this, "Successfully parsed template", $this->data);
    }

    /**
     * Parse template
     *
     * @param string $template Template content
     * @param bool $recurse Flag whether to parse recursivly
     *
     * @return string Parsed template
     */
    public function parse($template, $recurse = false)
    {
        if ($recurse) {
            $this->data['recurse'] = $recurse;
        }
        foreach ($this->data['tokens'] as $token => $value) {
            $token = $this->data['pre'] . $token . $this->data['post'];
            $template = str_replace($token, $value, $template);
        }

        if (!$this->data['recurse']) {
            return $template;
        }

        $recurseResult = $this->parse($template, false);
        if ($template <> $recurseResult) {
            return $this->parse($template);
        }

        return $template;
    }
}
