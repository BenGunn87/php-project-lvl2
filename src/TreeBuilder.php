<?php

namespace Differ\TreeBuilder;

use function Functional\sort;

const ADDED = 'added';
const REMOVED = 'removed';
const UPDATED = 'updated';
const NOT_CHANGED = 'notChanged';
const COMPLEX_VALUE = 'complexValue';

function createTreeNode(string $key, $value, string $action): array
{
    return [
        'key' => $key,
        'action' => $action,
        'value' => $value,
    ];
}

function createUpdatedTreeNode(string $key, $value, $newValue): array
{
    return [
        'key' => $key,
        'action' => UPDATED,
        'value' => $value,
        'newValue' => $newValue,
    ];
}

function createTreeNodeWithChildren(string $key, array $children, string $action): array
{
    return [
        'key' => $key,
        'action' => $action,
        'children' => array_values($children),
    ];
}

function processElem(string $key, $value, array $oldData, array $newData): array
{
    if (!array_key_exists($key, $oldData)) {
        $result = createTreeNode($key, $value, ADDED);
    } elseif (!array_key_exists($key, $newData)) {
        $result = createTreeNode($key, $value, REMOVED);
    } elseif ($value === $oldData[$key]) {
        $result = createTreeNode($key, $value, NOT_CHANGED);
    } elseif (is_object($value) && is_object($oldData[$key])) {
        $result = createTreeNodeWithChildren($key, createDiffTree($oldData[$key], $value), COMPLEX_VALUE);
    } else {
        $result = createUpdatedTreeNode($key, $oldData[$key], $value);
    }
    return $result;
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
