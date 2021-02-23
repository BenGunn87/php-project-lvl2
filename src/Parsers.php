<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function getParser(string $format): callable
{
    if ($format === 'yml') {
        return function (string $data): array {
            return Yaml::parse($data);
        };
    }

    return function (string $data): array {
        return json_decode($data, true);
    };
}

function parse(string $data, string $format): array
{
    $parser = getParser($format);
    return $parser($data);
}
