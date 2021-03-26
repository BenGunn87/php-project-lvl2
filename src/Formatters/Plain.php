<?php

namespace Differ\Formatters\Plain;

use Exception;

use const Differ\Formatters\Stylish\BAD_NODE_ACTION;
use const Differ\TreeBuilder\ADDED;
use const Differ\TreeBuilder\COMPLEX_VALUE;
use const Differ\TreeBuilder\NOT_CHANGED;
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
    ['key' => $key, 'action' => $action] = $node;
    $propertyFullName = "{$path}{$key}";
    $value = isset($node['value']) ? $node['value'] : '';
    $preparedValue = valueToString($value);
    switch ($action) {
        case ADDED:
            return "Property '$propertyFullName' was added with value: $preparedValue";
        case REMOVED:
            return "Property '$propertyFullName' was removed";
        case UPDATED:
            $newValue = $node['newValue'];
            return "Property '$propertyFullName' was updated. From $preparedValue to " . valueToString($newValue);
        case COMPLEX_VALUE:
            $children = $node['children'];
            return formattedToPlainIterator($children, "$propertyFullName.");
        case NOT_CHANGED:
            return "";
        default:
            throw new Exception("'$action' " . BAD_NODE_ACTION);
    }
}

function formattedToPlainIterator(array $tree, string $path): string
{
    $formattedData = array_map(fn($node): string => formattedNode($node, $path), $tree);
    $result = array_filter($formattedData, fn($item): bool => $item !== '');
    return implode(PHP_EOL, $result);
}

function formattedToPlain(array $tree): string
{
    return formattedToPlainIterator($tree, '');
}
