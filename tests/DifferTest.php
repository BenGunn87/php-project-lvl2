<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

use const Differ\Differ\BAD_FILE_NAME;
use const Differ\Formatters\BAD_FORMAT_EXCEPTION;
use const Differ\Formatters\JSON;
use const Differ\Formatters\PLAIN;
use const Differ\Formatters\STYLISH;
use const Differ\Parsers\BAD_DATA_FORMAT;

class DifferTest extends TestCase
{
    public function testGenDiffComplexJson()
    {
        $expectedDiff = file_get_contents($this->getAbsFilePath('expectedDiffComplexDataJson.txt'));
        $actualDiff = genDiff(
            $this->getAbsFilePath('oldComplexData.json'),
            $this->getAbsFilePath('newComplexData.json'),
            STYLISH
        );
        $this->assertEquals($expectedDiff, $actualDiff);
    }

    public function testGenDiffComplexYaml()
    {
        $expectedDiff = file_get_contents($this->getAbsFilePath('expectedDiffComplexDataYml.txt'));
        $actualDiff = genDiff(
            $this->getAbsFilePath('oldComplexData.yml'),
            $this->getAbsFilePath('newComplexData.yml'),
            STYLISH
        );
        $this->assertEquals($expectedDiff, $actualDiff);
    }

    public function testGenDiffPlainFormat()
    {
        $expectedDiff = file_get_contents($this->getAbsFilePath('expectedDiffPlainFormat.txt'));
        $actualDiff = genDiff(
            $this->getAbsFilePath('oldComplexData.json'),
            $this->getAbsFilePath('newComplexData.json'),
            PLAIN
        );
        $this->assertEquals($expectedDiff, $actualDiff);
    }

    public function testGenDiffJsonFormat()
    {
        $expectedDiff = file_get_contents($this->getAbsFilePath('diffComplexData.json'));
        $actualDiff = genDiff(
            $this->getAbsFilePath('oldComplexData.json'),
            $this->getAbsFilePath('newComplexData.json'),
            JSON
        );
        $this->assertEquals($expectedDiff, $actualDiff);
    }

    public function testGenDiffBadFormat()
    {
        $this->expectExceptionMessage(BAD_FORMAT_EXCEPTION);
        genDiff(
            $this->getAbsFilePath('oldComplexData.json'),
            $this->getAbsFilePath('newComplexData.json'),
            'test'
        );
    }

    public function testGenDiffBadFileFormat()
    {
        $this->expectExceptionMessage(BAD_DATA_FORMAT);
        genDiff(
            $this->getAbsFilePath('badFileFormat.jsn'),
            $this->getAbsFilePath('badFileFormat.jsn'),
            JSON
        );
    }

    public function testGenDiffBadFileName()
    {
        $this->expectExceptionMessage(BAD_FILE_NAME);
        genDiff(
            $this->getAbsFilePath('test.json'),
            $this->getAbsFilePath('test.json'),
            JSON
        );
    }

    private function getAbsFilePath(string $path): string
    {
        return __DIR__ . '/fixtures/' . $path;
    }
}
