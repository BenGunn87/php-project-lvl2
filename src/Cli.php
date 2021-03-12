<?php

namespace Differ\Cli;

use Docopt;
use Exception;

use function Differ\Differ\genDiff;

use const Differ\Formatters\STYLISH;

function getDoc(): string
{
    return <<<'DOCOPT'
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen
  -v --version                  Show version
  --format <fmt>                Report format [default: stylish]
DOCOPT;
}

function run(): bool
{
    $params = [
        'help' => true,
        'version' => '0.1.0'
    ];

    $args = Docopt::handle(getDoc(), $params);
    $formatName = $args['--format'] ?? STYLISH;
    try {
        $diff = genDiff($args['<firstFile>'], $args['<secondFile>'], $formatName);
        return print_r($diff);
    } catch (Exception $exception) {
        return print_r("Error: " . $exception->getMessage());
    }
}
