<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function getParser(string $format): callable
{
    if ($format === 'yml' || $format === 'yaml') {
        return fn(string $data): object => Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
    }

    return fn(string $data): object => json_decode($data);
}

function parse(string $data, string $format): object
{
    $parser = getParser($format);
    return $parser($data);
}
