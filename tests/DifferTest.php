<?php

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

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
        $actualDiff = genDiff($this->getAbsFilePath('oldComplexData.json'), $this->getAbsFilePath('newComplexData.json'));
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
        $actualDiff = genDiff($this->getAbsFilePath('oldComplexData.yml'), $this->getAbsFilePath('newComplexData.yml'));
        $this->assertEquals($expectedDiff, $actualDiff);
    }

    private function getAbsFilePath($path)
    {
        return __DIR__ . '/fixtures/' . $path;
    }
}
