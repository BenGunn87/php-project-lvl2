<?php

namespace Differ\Formatters\Plain;

use Exception;

use const Differ\Formatters\Stylish\BAD_NODE_TYPE;
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

function renderNode(array $node, string $path): string
{
    ['key' => $key, 'type' => $type] = $node;
    $propertyFullName = "{$path}{$key}";
    $value = array_key_exists('value', $node) ? $node['value'] : '';
    $preparedValue = valueToString($value);
    switch ($type) {
        case ADDED:
            return "Property '$propertyFullName' was added with value: $preparedValue";
        case REMOVED:
            return "Property '$propertyFullName' was removed";
        case UPDATED:
            $newValue = $node['newValue'];
            return "Property '$propertyFullName' was updated. From $preparedValue to " . valueToString($newValue);
        case COMPLEX_VALUE:
            $children = $node['children'];
            return renderToPlainIterator($children, "$propertyFullName.");
        case NOT_CHANGED:
            return "";
        default:
            throw new Exception("'$type' " . BAD_NODE_TYPE);
    }
}

function renderToPlainIterator(array $tree, string $path): string
{
    $formattedData = array_map(fn($node): string => renderNode($node, $path), $tree);
    $result = array_filter($formattedData, fn($item): bool => $item !== '');
    return implode(PHP_EOL, $result);
}

function renderToPlain(array $tree): string
{
    return renderToPlainIterator($tree, '');
}
