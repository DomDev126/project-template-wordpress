<?php

namespace Qobo\Robo\Command\Project;

use \Qobo\Robo\AbstractCommand;
use \Qobo\Robo\Formatter\RowsOfFields;

class Changelog extends AbstractCommand
{
    /**
     * Get project changelog
     *
     * @return string changelog
     */
    public function projectChangelog($opts = ['format' => 'table', 'fields' => ''])
    {
        $result = $this->taskProjectChangelog()
            ->format('--reverse --no-merges --pretty=format:"* %<(72,trunc)%s (%ad, %an)" --date=short')
            ->run();

        if (!$result->wasSuccessful()) {
            return false;
        }

        $data = $result->getData();

        $data = array_map(
            function ($str) {
                if (!preg_match("/^\*\s+(.*?)\((\d{4}-\d{2}-\d{2}), (.*?)\).*$/", $str, $matches)) {
                    return $str;
                }

                return [
                    'message'   => trim($matches[1]),
                    'data'      => $matches[2],
                    'author'    => $matches[3]
                ];
            },
            $data['data'][0]['output']
        );

        return new RowsOfFields($data);
    }
}
