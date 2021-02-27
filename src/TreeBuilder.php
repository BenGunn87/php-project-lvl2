<?php

namespace Differ\TreeBuilder;

const ADDED = 'added';
const REMOVED = 'removed';
const UPDATED = 'updated';
const NOT_CHANGED = 'notChanged';
const COMPLEX_VALUE = 'complexValue';

function createTreeNode(string $key, $value, string $action, int $level, $newValue = null): array
{
    return [
        'key' => $key,
        'action' => $action,
        'value' => $value,
        'newValue' => $newValue,
        'level' => $level,
    ];
}

function processElem(string $key, $value, array $oldData, array $newData, int $level): array
{
    if (!array_key_exists($key, $oldData)) {
        return [createTreeNode($key, $value, ADDED, $level)];
    }
    if (!array_key_exists($key, $newData)) {
        return [createTreeNode($key, $value, REMOVED, $level)];
    }
    if ($value === $oldData[$key]) {
        return [createTreeNode($key, $value, NOT_CHANGED, $level)];
    }
    if (is_object($value) && is_object($oldData[$key])) {
        return [createTreeNode(
            $key,
            createDiffTree($oldData[$key], $value, $level + 1),
            COMPLEX_VALUE,
            $level
        )];
    }
    return [createTreeNode($key, $oldData[$key], UPDATED, $level, $value)];
}

function createDiffTree(object $oldData, object $newData, int $level): array
{
    $oldDataArr = (array) $oldData;
    $newDataArr = (array) $newData;
    $union = array_merge($oldDataArr, $newDataArr);
    ksort($union);
    $result = [];
    foreach ($union as $key => $value) {
        $result = array_merge($result, processElem($key, $value, $oldDataArr, $newDataArr, $level));
    }

    return $result;
}

function getDiffTree(object $oldData, object $newData): array
{
    return createDiffTree($oldData, $newData, 0);
}
