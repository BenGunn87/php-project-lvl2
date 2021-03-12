<?php

namespace Differ\Parsers;

use Exception;
use Symfony\Component\Yaml\Yaml;

const YML = 'yml';
const YAML = 'yaml';
const JSON = 'json';
const BAD_DATA_FORMAT = 'Bad data format.';

function getParser(string $format): callable
{
    switch (strtolower($format)) {
        case YML:
        case YAML:
            return fn(string $data): object => Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
        case JSON:
            return fn(string $data): object => json_decode($data);
        default:
            throw new Exception(BAD_DATA_FORMAT);
    }
}

function parse(string $data, string $format): object
{
    $parser = getParser($format);
    return $parser($data);
}
