<?php

namespace Differ\TreeBuilder;

const ADDED = 'added';
const REMOVED = 'removed';
const UPDATED = 'updated';
const NOT_CHANGED = 'notChanged';
const COMPLEX_VALUE = 'complexValue';

function createTreeNode(string $key, $value, string $action, $newValue = null): array
{
    $result = [
        'key' => $key,
        'action' => $action,
        'value' => $value,
    ];
    if ($newValue !== null) {
        $result['newValue'] = $newValue;
    }
    return $result;
}

function processElem(string $key, $value, array $oldData, array $newData): array
{
    if (!array_key_exists($key, $oldData)) {
        return [createTreeNode($key, $value, ADDED)];
    }
    if (!array_key_exists($key, $newData)) {
        return [createTreeNode($key, $value, REMOVED)];
    }
    if ($value === $oldData[$key]) {
        return [createTreeNode($key, $value, NOT_CHANGED)];
    }
    if (is_object($value) && is_object($oldData[$key])) {
        return [createTreeNode(
            $key,
            createDiffTree($oldData[$key], $value),
            COMPLEX_VALUE
        )];
    }
    return [createTreeNode($key, $oldData[$key], UPDATED, $value)];
}

function createDiffTree(object $oldData, object $newData): array
{
    $oldDataArr = (array) $oldData;
    $newDataArr = (array) $newData;
    $union = array_merge($oldDataArr, $newDataArr);
    ksort($union);
    $result = [];
    foreach ($union as $key => $value) {
        $result = array_merge($result, processElem($key, $value, $oldDataArr, $newDataArr));
    }

    return $result;
}
