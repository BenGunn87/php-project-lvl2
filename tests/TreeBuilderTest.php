<?php

use PHPUnit\Framework\TestCase;
use function Differ\TreeBuilder\createDiffTree;
use const Differ\TreeBuilder\ADDED;
use const Differ\TreeBuilder\NOT_CHANGED;
use const Differ\TreeBuilder\REMOVED;
use const Differ\TreeBuilder\UPDATED;

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
                'action' => NOT_CHANGED,
                'value' => 100,
            ],
            [
                'key' => 'key2',
                'action' => UPDATED,
                'value' => 'test',
                'newValue' => 'test_2',
            ],
            [
                'key' => 'key3',
                'action' => REMOVED,
                'value' => true,
            ],
            [
                'key' => 'key4',
                'action' => ADDED,
                'value' => true,
            ],
        ];
        $tree = createDiffTree((object) $oldData, (object) $newData);
        $this->assertEquals($expectedTree, $tree);
    }
}