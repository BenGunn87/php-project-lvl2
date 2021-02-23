<?php

namespace Differ;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function test_genDiff_jsonFile()
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
        $actualDiff = genDiff('./tests/fixtures/file1.json', './tests/fixtures/file2.json');
        $this->assertEquals($expectedDiff, $actualDiff);
    }

    public function test_genDiff_ymlFile()
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
        $actualDiff = genDiff('./tests/fixtures/oldData1.yml', './tests/fixtures/newData1.yml');
        $this->assertEquals($expectedDiff, $actualDiff);
    }
}
