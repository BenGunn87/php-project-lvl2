<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiff()
    {
        $expectedDiff = <<<'EOD'
{
- follow: false
  host: hexlet.io
- proxy: 123.234.53.22
- timeout: 50
+ timeout: 20
+ verbose: true
}
EOD;
        $actualDiff = genDiff('./src/Tests/fixtures/file1.json', './src/Tests/fixtures/file2.json');
        $this->assertEquals($expectedDiff, $actualDiff);
    }
}
