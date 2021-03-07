<?php

namespace Differ\Formatters;

use function Differ\Formatters\Json\formattedToJson;
use function Differ\Formatters\Plain\formattedToPlain;
use function Differ\Formatters\Stylish\formattedToStylish;

const STYLISH = 'stylish';
const PLAIN = 'plain';
const JSON = 'json';

function getFormatter(string $formatName): callable
{
    switch ($formatName) {
        case PLAIN:
            return fn(array $tree): string => formattedToPlain($tree);
        case JSON:
            return fn(array $tree): string => formattedToJson($tree);
        default:
            return fn(array $tree): string => formattedToStylish($tree);
    }
}
