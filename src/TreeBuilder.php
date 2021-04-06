<?php

namespace Differ\TreeBuilder;

use function Functional\sort;

const ADDED = 'added';
const REMOVED = 'removed';
const UPDATED = 'updated';
const NOT_CHANGED = 'notChanged';
const COMPLEX_VALUE = 'complexValue';

function createTreeNode(string $key, $value, string $type): array
{
    return [
        'key' => $key,
        'type' => $type,
        'value' => $value,
    ];
}

function createUpdatedTreeNode(string $key, $value, $newValue): array
{
    return [
        'key' => $key,
        'type' => UPDATED,
        'value' => $value,
        'newValue' => $newValue,
    ];
}

function createTreeNodeWithChildren(string $key, array $children, string $type): array
{
    return [
        'key' => $key,
        'type' => $type,
        'children' => array_values($children),
    ];
}

function processElem(string $key, $value, array $oldData, array $newData): array
{
    if (!array_key_exists($key, $oldData)) {
        return createTreeNode($key, $value, ADDED);
    }
    if (!array_key_exists($key, $newData)) {
        return createTreeNode($key, $value, REMOVED);
    }
    if ($value === $oldData[$key]) {
        return createTreeNode($key, $value, NOT_CHANGED);
    }
    if (is_object($value) && is_object($oldData[$key])) {
        return createTreeNodeWithChildren($key, createDiffTree($oldData[$key], $value), COMPLEX_VALUE);
    }
    return createUpdatedTreeNode($key, $oldData[$key], $value);
}

function createDiffTree(object $oldData, object $newData): array
{
    $preparedOldData = (array) $oldData;
    $preparedNewData = (array) $newData;
    $unionAllData = array_merge($preparedOldData, $preparedNewData);
    $sortedKey = sort(array_keys($unionAllData), fn(string $left, string $right): int => $left <=> $right);

    return array_map(
        fn(string $key): array => processElem($key, $unionAllData[$key], $preparedOldData, $preparedNewData),
        $sortedKey
    );
}
