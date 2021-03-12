<?php

namespace Differ\Formatters;

use Exception;

use function Differ\Formatters\Json\formattedToJson;
use function Differ\Formatters\Plain\formattedToPlain;
use function Differ\Formatters\Stylish\formattedToStylish;

const STYLISH = 'stylish';
const PLAIN = 'plain';
const JSON = 'json';
const BAD_FORMAT_EXCEPTION = "Format not supported.";

function getFormatter(string $formatName): callable
{
    switch ($formatName) {
        case PLAIN:
            return fn(array $tree): string => formattedToPlain($tree);
        case JSON:
            return fn(array $tree): string => formattedToJson($tree);
        case STYLISH:
            return fn(array $tree): string => formattedToStylish($tree);
        default:
            throw new Exception("'$formatName' " . BAD_FORMAT_EXCEPTION);
    }
}
