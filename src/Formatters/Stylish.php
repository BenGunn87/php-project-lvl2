<?php

namespace Differ\Formatters\Stylish;

use const Differ\TreeBuilder\ADDED;
use const Differ\TreeBuilder\COMPLEX_VALUE;
use const Differ\TreeBuilder\REMOVED;
use const Differ\TreeBuilder\UPDATED;

const INDENT_CHARS = '    ';
const ADDED_ITEM_PREFIX = '  + ';
const REMOVED_ITEM_PREFIX = '  - ';
const NOT_CHANGED_ITEM_PREFIX = INDENT_CHARS;

function objectToString($value, int $level = 0): string
{
    $indent = str_repeat(INDENT_CHARS, $level);
    $indentForValue = str_repeat(INDENT_CHARS, $level + 1);
    $preparedData = (array) $value;
    $result = array_map(
        function (string $key, $item) use ($indentForValue, $level): string {
            return "{$indentForValue}{$key}: " . valueToString($item, $level);
        },
        array_keys($preparedData),
        $preparedData
    );

    return implode(
        PHP_EOL,
        array_merge(["{"], $result, ["$indent}"])
    );
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
    if ($action === COMPLEX_VALUE) {
        $children = $node['children'];
        return $indent . NOT_CHANGED_ITEM_PREFIX . "$key: " . stylishWithLevel($children, $level + 1);
    }

    $preparedValue = valueToString($node['value'], $level);
    switch ($action) {
        case ADDED:
            $result = $indent . ADDED_ITEM_PREFIX . "$key: $preparedValue";
            break;
        case REMOVED:
            $result = $indent . REMOVED_ITEM_PREFIX . "$key: $preparedValue";
            break;
        case UPDATED:
            $newValue = $node['newValue'];
            $result = $indent . REMOVED_ITEM_PREFIX . "$key: $preparedValue" . PHP_EOL .
                $indent . ADDED_ITEM_PREFIX . "$key: " . valueToString($newValue, $level);
            break;
        default:
            $result = $indent . NOT_CHANGED_ITEM_PREFIX . "$key: $preparedValue";
    }

    return $result;
}

function stylishWithLevel(array $tree, int $level): string
{
    $result = array_map(function ($node) use ($level): string {
        return stylishNode($node, $level);
    }, $tree);
    $indent = str_repeat(INDENT_CHARS, $level);
    return implode(
        PHP_EOL,
        array_merge(['{'], $result, ["$indent}"])
    );
}

function formattedToStylish(array $tree): string
{
    return stylishWithLevel($tree, 0);
}
