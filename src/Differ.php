<?php

namespace Differ\Differ;

use Exception;

use function Differ\Formatters\getRender;
use function Differ\Parsers\parse;
use function Differ\TreeBuilder\createDiffTree;

use const Differ\Formatters\STYLISH;

const BAD_FILE_NAME = 'File not found.';

function getDataFromFile(string $path): object
{
    $absolutePath = $path[0] === '/' ? $path : getcwd() . "/$path";
    if (!file_exists($absolutePath)) {
        throw new Exception("'$path' " . BAD_FILE_NAME);
    }
    $content = file_get_contents($absolutePath);
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    return parse((string) $content, $ext);
}

function genDiff(string $pathToOldFile, string $pathToNewFile, string $formatName = STYLISH): string
{
    $oldData = getDataFromFile($pathToOldFile);
    $newData = getDataFromFile($pathToNewFile);
    $diffTree = createDiffTree($oldData, $newData);
    $render = getRender($formatName);
    return $render($diffTree);
}
