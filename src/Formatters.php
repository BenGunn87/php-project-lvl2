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
            return function (array $tree): string {
                return formattedToPlain($tree);
            };
        case JSON:
            return function (array $tree): string {
                return formattedToJson($tree);
            };
        default:
            return function (array $tree): string {
                return formattedToStylish($tree);
            };
    }
}
