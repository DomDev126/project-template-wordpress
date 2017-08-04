<?php

namespace Qobo\Robo\Task\Template;

use Robo\Result;

/**
 * List all tokens from template file
 *
 * ```php
 * <?php
 * $this->taskTemplateFileTokensList()
 * ->path('template.ctp')
 * ->wrap('###')
 * ->run();
 *
 *<?php
 * $this->taskTemplateFileTokensList()
 * ->path('template.ctp')
 * ->pre('{{')
 * ->post('}}')
 * ->run();
 *
 * ?>
 * ```
 */
class FileTokensList extends \Qobo\Robo\AbstractTask
{
    /**
     * {@inheritdoc}
     */
    protected $data = [
        'path'          => null,
        'pre'           => '%%',
        'post'          => '%%'
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

        $this->printInfo("Retrieving list of tockens from {path} template", $this->data);
        if (!is_file($this->data['path']) || !is_readable($this->data['path'])) {
            return Result::error($this, "Template does not exist or is not readable", $this->data);
        }

        $file = file($this->data['path']);
        if (empty($file)) {
            return Result::success($this, "Template is empty, nothing to read");
        }

        $this->data['data'] = [];
        $file = file_get_contents($this->data['path']);
        if (preg_match_all('/' . $this->data['pre'] . '(.*?)' . $this->data['post'] . '/', $file, $matches)) {
            $this->data['data'] = array_unique($matches[1]);
        }

        return Result::success($this, "Successfully reloaded environment", $this->data);
    }
}
