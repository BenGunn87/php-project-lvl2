<?php

namespace Differ\Formatters;

use function Differ\Formatters\Plain\formattedToPlain;
use function Differ\Formatters\Stylish\stylish;

const STYLISH = 'stylish';
const PLAIN = 'plain';

function getFormatter(string $formatName): callable
{
    switch ($formatName) {
        case PLAIN:
            return function (array $tree): string {
                return formattedToPlain($tree);
            };
        default:
            return function (array $tree): string {
                return stylish($tree);
            };
    }
}
