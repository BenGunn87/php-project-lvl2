<?php

namespace Differ\TreeBuilder;

use function Funct\Collection\sortBy;
use function Funct\Collection\zip;

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
        'children' => $children,
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
        $result = createTreeNodeWithChildren(
            $key,
            createDiffTree($oldData[$key], $value),
            COMPLEX_VALUE
        );
    } else {
        $result = createUpdatedTreeNode($key, $oldData[$key], $value);
    }
    return $result;
}

function getUnionData(array $oldData, array $newData): array
{
    $union = array_merge($oldData, $newData);
    $preparedUnionData = array_map(function (string $key, $value): array {
        return [$key, $value];
    }, array_keys($union), $union);
    $sortedData = sortBy($preparedUnionData, function (array $item): string {
        [$key] = $item;
        return $key;
    });
    return array_values($sortedData);
}

function createDiffTree(object $oldData, object $newData): array
{
    $preparedOldData = (array) $oldData;
    $preparedNewData = (array) $newData;
    $unionData = getUnionData($preparedOldData, $preparedNewData);
    return array_map(
        function (array $item) use ($preparedOldData, $preparedNewData): array {
            [$key, $value] = $item;
            return processElem($key, $value, $preparedOldData, $preparedNewData);
        },
        $unionData
    );
}
