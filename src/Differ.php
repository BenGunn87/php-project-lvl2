<?php

namespace Differ\Differ;

use function Differ\Formatters\getFormatter;
use function Differ\Parsers\parse;
use function Differ\TreeBuilder\createDiffTree;

function getDataFromFile(string $path): object
{
    $absolutePath = $path[0] === '/' ? $path : getcwd() . '/' . $path;
    $content = file_get_contents($absolutePath);
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    return parse($content, $ext);
}

function genDiff(string $pathToOldFile, string $pathToNewFile, string $formatName): string
{
    $oldData = getDataFromFile($pathToOldFile);
    $newData = getDataFromFile($pathToNewFile);
    $diffTree = createDiffTree($oldData, $newData);
    $formatter = getFormatter($formatName);
    return $formatter($diffTree);
}
