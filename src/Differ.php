<?php

namespace Differ\Differ;

use function Differ\Formatters\getFormatter;
use function Differ\Parsers\parse;
use function Differ\TreeBuilder\createDiffTree;

use const Differ\Formatters\STYLISH;

function getDataFromFile(string $path): object
{
    $absolutePath = $path[0] === '/' ? $path : getcwd() . "/$path";
    $content = file_get_contents($absolutePath);
    if ($content === false) {
        return (object) [];
    }
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    return parse($content, $ext);
}

function genDiff(string $pathToOldFile, string $pathToNewFile, string $formatName = STYLISH): string
{
    $oldData = getDataFromFile($pathToOldFile);
    $newData = getDataFromFile($pathToNewFile);
    $diffTree = createDiffTree($oldData, $newData);
    $formatter = getFormatter($formatName);
    return $formatter($diffTree);
}
