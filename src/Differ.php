<?php

namespace Differ\Differ;

use Exception;

use function Differ\Formatters\getFormatter;
use function Differ\Parsers\parse;
use function Differ\TreeBuilder\createDiffTree;

use const Differ\Formatters\STYLISH;

const BAD_FILE_NAME = 'File not found.';

function getDataFromFile(string $path): object
{
    $absolutePath = $path[0] === '/' ? $path : getcwd() . "/$path";
    try {
        $content = file_get_contents($absolutePath);
    } catch (Exception $e) {
        throw new Exception("'$path' " . BAD_FILE_NAME);
    }
    if ($content === false) {
        throw new Exception("'$path' " . BAD_FILE_NAME);
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
