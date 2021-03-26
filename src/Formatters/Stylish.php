<?php

namespace Differ\Formatters\Stylish;

use Exception;

use const Differ\TreeBuilder\ADDED;
use const Differ\TreeBuilder\COMPLEX_VALUE;
use const Differ\TreeBuilder\NOT_CHANGED;
use const Differ\TreeBuilder\REMOVED;
use const Differ\TreeBuilder\UPDATED;

const INDENT_CHARS = '    ';
const ADDED_ITEM_PREFIX = '  + ';
const REMOVED_ITEM_PREFIX = '  - ';
const NOT_CHANGED_ITEM_PREFIX = INDENT_CHARS;
const BAD_NODE_ACTION = 'Node action not supported.';

function objectToString($value, int $level = 0): string
{
    $indent = str_repeat(INDENT_CHARS, $level);
    $indentForValue = str_repeat(INDENT_CHARS, $level + 1);
    $preparedData = (array) $value;
    $result = array_map(
        fn(string $key): string => "{$indentForValue}{$key}: " . valueToString($preparedData[$key], $level),
        array_keys($preparedData)
    );

    return implode(PHP_EOL, ["{", ...$result, "$indent}"]);
}

function simpleValueToString($value): string
{
    if (is_bool($value)) {
        return $value ? "true" : "false";
    }
    if ($value === null) {
        return "null";
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
    ['key' => $key, 'action' => $action] = $node;
    $indent = str_repeat(INDENT_CHARS, $level);
    $value = array_key_exists('value', $node) ? $node['value'] : '';
    $preparedNodeValue = "$key: " . valueToString($value, $level);
    switch ($action) {
        case ADDED:
            return $indent . ADDED_ITEM_PREFIX . $preparedNodeValue;
        case REMOVED:
            return $indent . REMOVED_ITEM_PREFIX . $preparedNodeValue;
        case UPDATED:
            $newValue = $node['newValue'];
            return $indent . REMOVED_ITEM_PREFIX . $preparedNodeValue . PHP_EOL .
                $indent . ADDED_ITEM_PREFIX . "$key: " . valueToString($newValue, $level);
        case COMPLEX_VALUE:
            $children = $node['children'];
            return $indent . NOT_CHANGED_ITEM_PREFIX . "$key: " . stylishWithLevel($children, $level + 1);
        case NOT_CHANGED:
            return $indent . NOT_CHANGED_ITEM_PREFIX . $preparedNodeValue;
        default:
            throw new Exception("'$action' " . BAD_NODE_ACTION);
    }
}

function stylishWithLevel(array $tree, int $level): string
{
    $indent = str_repeat(INDENT_CHARS, $level);
    $result = array_map(fn($node): string => stylishNode($node, $level), $tree);
    return implode(PHP_EOL, ['{', ...$result, "$indent}"]);
}

function formattedToStylish(array $tree): string
{
    return stylishWithLevel($tree, 0);
}
