<?php

namespace Differ\Formatters\Json;

function formattedToJson(array $tree): string
{
    $result = json_encode($tree, JSON_PRETTY_PRINT);
    return $result === false ? '' : $result;
}
