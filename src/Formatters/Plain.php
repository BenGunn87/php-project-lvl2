<?php

namespace Differ\Formatters\Plain;

use const Differ\TreeBuilder\ADDED;
use const Differ\TreeBuilder\COMPLEX_VALUE;
use const Differ\TreeBuilder\REMOVED;
use const Differ\TreeBuilder\UPDATED;

function simpleValueToString($value): string
{
    if (is_bool($value)) {
        return $value ? "true" : "false";
    }
    if ($value === null) {
        return 'null';
    }
    if (is_string($value)) {
        return "'$value'";
    }
    return (string) $value;
}

function valueToString($value): string
{
    return is_object($value)
        ? "[complex value]"
        : simpleValueToString($value);
}

function formattedNode(array $node, string $path): string
{
    ['key' => $key, 'action' => $action, 'value' => $value, 'level' => $level, 'newValue' => $newValue] = $node;
    switch ($action) {
        case ADDED:
            return "Property '{$path}{$key}' was added with value: " . valueToString($value);
        case REMOVED:
            return "Property '{$path}{$key}' was removed";
        case UPDATED:
            return "Property '{$path}{$key}' was updated. From " . valueToString($value) .
                " to " . valueToString($newValue);
        case COMPLEX_VALUE:
            return formattedToPlainIterator($value, "{$path}{$key}.");
        default:
            return "";
    }
}

function formattedToPlainIterator(array $tree, string $path): string
{
    $result = array_map(function ($node) use ($path) {
        return formattedNode($node, $path);
    }, $tree);
    $result = array_filter($result, function ($item) {
        return $item !== '';
    });
    return implode(PHP_EOL, $result);
}

function formattedToPlain(array $tree): string
{
    return formattedToPlainIterator($tree, '');
}
