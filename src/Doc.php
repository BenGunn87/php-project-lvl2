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

Options:
  -h --help                     Show this screen
  -v --version                  Show version
DOCOPT;

    $params = [
        'help' => true,
        'version' => '0.1.0'
    ];

    Docopt::handle($doc, $params);
}
