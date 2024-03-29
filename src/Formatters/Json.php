<?php

namespace Differ\Formatters\Json;

function renderToJson(array $tree): string
{
    return json_encode($tree, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
}
