<?php

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;
use const Differ\Formatters\JSON;
use const Differ\Formatters\PLAIN;
use const Differ\Formatters\STYLISH;

class DifferTest extends TestCase
{
    public function test_genDiff_complexJson()
    {
        $expectedDiff = <<<'EOD'
{
    common: {
      + follow: false
        setting1: Value 1
      - setting2: 200
      - setting3: true
      + setting3: null
      + setting4: blah blah
      + setting5: {
            key5: value5
        }
        setting6: {
            doge: {
              - wow: 
              + wow: so much
            }
            key: value
          + ops: vops
        }
    }
    group1: {
      - baz: bas
      + baz: bars
        foo: bar
      - nest: {
            key: value
        }
      + nest: str
    }
  - group2: {
        abc: 12345
        deep: {
            id: 45
        }
    }
  + group3: {
        deep: {
            id: {
                number: 45
            }
        }
        fee: 100500
    }
}
EOD;
        $actualDiff = genDiff(
            $this->getAbsFilePath('oldComplexData.json'),
            $this->getAbsFilePath('newComplexData.json'),
            STYLISH
        );
        $this->assertEquals($expectedDiff, $actualDiff);
    }

    public function test_genDiff_complexYaml()
    {
        $expectedDiff = <<<'EOD'
{
    common: {
      + follow: false
        setting1: Value 1
      + setting5: {
            key5: value5
        }
        setting6: {
            doge: {
              - wow: 
              + wow: so much
            }
            key: value
          + ops: vops
        }
    }
    group1: {
      - baz: bas
      + foo: bar
      - nest: {
            key: value
        }
    }
  - group2: {
        abc: 12345
        deep: {
            id: 45
        }
    }
  + group3: {
        deep: {
            id: {
                number: 45
            }
        }
    }
}
EOD;
        $actualDiff = genDiff(
            $this->getAbsFilePath('oldComplexData.yml'),
            $this->getAbsFilePath('newComplexData.yml'),
            STYLISH
        );
        $this->assertEquals($expectedDiff, $actualDiff);
    }

    public function test_genDiff_complexJson_plainFormat()
    {
        $expectedDiff = <<<'EOD'
Property 'common.follow' was added with value: false
Property 'common.setting2' was removed
Property 'common.setting3' was updated. From true to null
Property 'common.setting4' was added with value: 'blah blah'
Property 'common.setting5' was added with value: [complex value]
Property 'common.setting6.doge.wow' was updated. From '' to 'so much'
Property 'common.setting6.ops' was added with value: 'vops'
Property 'group1.baz' was updated. From 'bas' to 'bars'
Property 'group1.nest' was updated. From [complex value] to 'str'
Property 'group2' was removed
Property 'group3' was added with value: [complex value]
EOD;
        $actualDiff = genDiff(
            $this->getAbsFilePath('oldComplexData.json'),
            $this->getAbsFilePath('newComplexData.json'),
            PLAIN
        );
        $this->assertEquals($expectedDiff, $actualDiff);
    }

    public function test_genDiff_complexJson_jsonFormat()
    {
        $expectedDiff = file_get_contents($this->getAbsFilePath('diffComplexData.json'));
        $actualDiff = genDiff(
            $this->getAbsFilePath('oldComplexData.json'),
            $this->getAbsFilePath('newComplexData.json'),
            JSON
        );
        $this->assertEquals($expectedDiff, $actualDiff);
    }

    private function getAbsFilePath(string $path): string
    {
        return __DIR__ . '/fixtures/' . $path;
    }
}
