<?php

namespace Differ\Differ;

function varToString($value): string
{
    if (is_bool($value)) {
        return $value ? "true" : "false";
    }
    return (string) $value;
}

function checkElem($key, $value, $oldData, $newData): string
{
    if (!array_key_exists($key, $oldData)) {
        return "+ $key: " . varToString($value);
    }
    if (!array_key_exists($key, $newData)) {
        return "- $key: " . varToString($value);
    }
    if ($value === $oldData[$key]) {
        return "  $key: " . varToString($value);
    }
    return "- $key: " . varToString($oldData[$key]) . PHP_EOL . "+ $key: " . varToString($value);
}

function calcDiff(array $oldData, array $newData): string
{
    $union = array_merge($oldData, $newData);
    ksort($union);
    $result = ['{'];
    foreach ($union as $key => $value) {
        $result[] = checkElem($key, $value, $oldData, $newData);
    }
    $result[] = '}';
    return implode(PHP_EOL, $result);
}

function getDataFromFile(string $path)
{
    $absolutePath = $path[0] === '/' ? $path : getcwd() . '/' . $path;
    $content = file_get_contents($absolutePath);
    return json_decode($content, true);
}

function genDiff(string $pathToOldFile, string $pathToNewFile): void
{
    $data1 = getDataFromFile($pathToOldFile);
    $data2 = getDataFromFile($pathToNewFile);
    print_r(calcDiff($data1, $data2));
}
