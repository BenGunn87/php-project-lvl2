<?php

use const Differ\TreeBuilder\ADDED;
use const Differ\TreeBuilder\COMPLEX_VALUE;
use const Differ\TreeBuilder\DELETED;

function objectToString($value, int $level = 0)
{
    $result = ['{'];
    $indent = str_repeat('    ', $level);
    foreach ((array) $value as $key => $item) {
        $result[] = "$indent    $key: " . varToString($item, $level);
    }
    $result[] = "$indent}";
    return implode(PHP_EOL, $result);
}

function varToString($value, int $level = 0): string
{
    if (is_bool($value)) {
        return $value ? "true" : "false";
    }
    if ($value === null) {
        return 'null';
    }
    if (is_object($value)) {
        return objectToString($value, $level + 1);
    }
    return (string) $value;
}

function stylishNode(array $node): string
{
    ['key' => $key, 'action' => $action, 'value' => $value, 'level' => $level] = $node;
    $indent = str_repeat('    ', $level);
    switch ($action) {
        case ADDED:
            return "$indent  + $key: " . varToString($value, $level);
        case DELETED:
            return "$indent  - $key: " . varToString($value, $level);
        case COMPLEX_VALUE:
            return "$indent    $key: " . stylishWithLevel($value, $level);
        default:
            return "$indent    $key: " . varToString($value, $level);
    }
}

function stylishWithLevel(array $tree, int $level): string
{
    $stylishTree = array_map(function ($node) {
        return stylishNode($node);
    }, $tree);
    $indent = $level === -1
        ? ''
        : str_repeat('    ', $level + 1);
    $result = array_merge(['{'], $stylishTree, ["$indent}"]);
    return implode(PHP_EOL, $result);
}

function stylish(array $tree): string
{
    return stylishWithLevel($tree, -1);
}
