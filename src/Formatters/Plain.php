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
    ['key' => $key, 'action' => $action] = $node;
    $propertyFullName = "{$path}{$key}";
    if ($action === COMPLEX_VALUE) {
        $children = $node['children'];
        return formattedToPlainIterator($children, "$propertyFullName.");
    }
    $preparedValue = valueToString($node['value']);
    switch ($action) {
        case ADDED:
            $result = "Property '$propertyFullName' was added with value: $preparedValue";
            break;
        case REMOVED:
            $result = "Property '$propertyFullName' was removed";
            break;
        case UPDATED:
            $newValue = $node['newValue'];
            $result = "Property '$propertyFullName' was updated. From $preparedValue to " . valueToString($newValue);
            break;
    }
    return $result ?? "";
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
