<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../helpers.php';

class MessageFilterTest extends TestCase
{
    public function testFilterCholera(): void
    {
        $input = "To jest cholera test!";
        $expected = "To jest co przeklinasz? test!";
        $this->assertEquals($expected, filterMessage($input));
    }

    public function testFilterMultipleOccurrences(): void
    {
        $input = "Cholera cholera!";
        $expected = "co przeklinasz? co przeklinasz?";
        $this->assertEquals($expected, filterMessage($input));
    }

    public function testCaseInsensitive(): void
    {
        $input = "CHOlera!";
        $expected = "co przeklinasz?!";
        $this->assertEquals($expected, filterMessage($input));
    }

    public function testNoBadWords(): void
    {
        $input = "Wszystko OK!";
        $expected = "Wszystko OK!";
        $this->assertEquals($expected, filterMessage($input));
    }
}
