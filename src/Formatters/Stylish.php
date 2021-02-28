<?php

namespace Differ\Formatters\Stylish;

use const Differ\TreeBuilder\ADDED;
use const Differ\TreeBuilder\COMPLEX_VALUE;
use const Differ\TreeBuilder\REMOVED;
use const Differ\TreeBuilder\UPDATED;

function objectToString($value, int $level = 0): string
{
    $result = ['{'];
    $indent = str_repeat('    ', $level);
    foreach ((array) $value as $key => $item) {
        $result[] = "$indent    $key: " . valueToString($item, $level);
    }
    $result[] = "$indent}";
    return implode(PHP_EOL, $result);
}

function simpleValueToString($value): string
{
    if (is_bool($value)) {
        return $value ? "true" : "false";
    }
    if ($value === null) {
        return 'null';
    }
    return (string) $value;
}

function valueToString($value, int $level = 0): string
{
    return is_object($value)
        ? objectToString($value, $level + 1)
        : simpleValueToString($value);
}

function stylishNode(array $node, int $level): string
{
    ['key' => $key, 'action' => $action, 'value' => $value] = $node;
    $indent = str_repeat('    ', $level);
    switch ($action) {
        case ADDED:
            return "$indent  + $key: " . valueToString($value, $level);
        case REMOVED:
            return "$indent  - $key: " . valueToString($value, $level);
        case UPDATED:
            $newValue = $node['newValue'];
            return "$indent  - $key: " . valueToString($value, $level) . PHP_EOL .
                "$indent  + $key: " . valueToString($newValue, $level);
        case COMPLEX_VALUE:
            return "$indent    $key: " . stylishWithLevel($value, $level + 1);
        default:
            return "$indent    $key: " . valueToString($value, $level);
    }
}

function stylishWithLevel(array $tree, int $level): string
{
    $stylishTree = array_map(function ($node) use ($level) {
        return stylishNode($node, $level);
    }, $tree);
    $indent = str_repeat('    ', $level);
    $result = array_merge(['{'], $stylishTree, ["$indent}"]);
    return implode(PHP_EOL, $result);
}

function stylish(array $tree): string
{
    return stylishWithLevel($tree, 0);
}
