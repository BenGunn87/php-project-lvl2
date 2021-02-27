<?php

use PHPUnit\Framework\TestCase;
use function Differ\TreeBuilder\getDiffTree;

class TreeBuilderTest extends TestCase
{
    public function test_createDiffTree()
    {
        $oldData = [
            'key1' => 100,
            'key2' => 'test',
            'key3' => true
        ];
        $newData = [
            'key1' => 100,
            'key2' => 'test_2',
            'key4' => true
        ];
        $expectedTree = [
            [
                'key' => 'key1',
                'action' => 'notChanged',
                'value' => 100,
                'level' => 0,
            ],
            [
                'key' => 'key2',
                'action' => 'deleted',
                'value' => 'test',
                'level' => 0,
            ],
            [
                'key' => 'key2',
                'action' => 'added',
                'value' => 'test_2',
                'level' => 0,
            ],
            [
                'key' => 'key3',
                'action' => 'deleted',
                'value' => true,
                'level' => 0,
            ],
            [
                'key' => 'key4',
                'action' => 'added',
                'value' => true,
                'level' => 0,
            ],
        ];
        $tree = getDiffTree((object) $oldData, (object) $newData);
        $this->assertEquals($expectedTree, $tree);
    }
}