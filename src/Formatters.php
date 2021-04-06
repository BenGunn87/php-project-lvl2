<?php

namespace Differ\Formatters;

use Exception;

use function Differ\Formatters\Json\renderToJson;
use function Differ\Formatters\Plain\renderToPlain;
use function Differ\Formatters\Stylish\renderToStylish;

const STYLISH = 'stylish';
const PLAIN = 'plain';
const JSON = 'json';
const BAD_FORMAT_EXCEPTION = "Format not supported.";

function getRender(string $formatName): callable
{
    switch ($formatName) {
        case PLAIN:
            return fn(array $tree): string => renderToPlain($tree);
        case JSON:
            return fn(array $tree): string => renderToJson($tree);
        case STYLISH:
            return fn(array $tree): string => renderToStylish($tree);
        default:
            throw new Exception("'$formatName' " . BAD_FORMAT_EXCEPTION);
    }
}
