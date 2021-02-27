<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function getParser(string $format): callable
{
    if ($format === 'yml') {
        return function (string $data): object {
            return Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
        };
    }

    return function (string $data): object {
        return json_decode($data);
    };
}

function parse(string $data, string $format): object
{
    $parser = getParser($format);
    return $parser($data);
}
