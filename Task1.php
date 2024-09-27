<?php

class Task1
{
    public static function parse($text)
    {
        $pattern = '|\[(\w+)(?::(.*))?](.*)\[/\1]|';
        $result = [];
        if (preg_match_all($pattern, str_replace(PHP_EOL, '{PHP_EOL}', $text), $matches)) {
            foreach ($matches[1] as $key => $tagName) {
                $description = $matches[2][$key];
                $data = $matches[3][$key];

                $result[$tagName] = [
                    'description' => str_replace('{PHP_EOL}', PHP_EOL, $description),
                    'data' => str_replace('{PHP_EOL}', PHP_EOL, $data),
                ];
            }
        }
        return $result;
    }
}