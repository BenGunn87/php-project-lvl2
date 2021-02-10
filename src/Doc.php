<?php

namespace Gendiff\Doc;

use Docopt;

function getDoc()
{
    $doc = <<<'DOCOPT'
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

    $params = [
        'help' => true,
        'version' => '0.1.0'
    ];

    Docopt::handle($doc, $params);
}
