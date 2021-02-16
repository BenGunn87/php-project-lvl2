<?php

namespace Differ\Doc;

use Docopt;

use function Differ\Differ\genDiff;

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

function run(): void
{
    $params = [
        'help' => true,
        'version' => '0.1.0'
    ];

    $args = Docopt::handle(getDoc(), $params);
    if (isset($args['<firstFile>'])) {
        genDiff($args['<firstFile>'], $args['<secondFile>']);
    }
}
