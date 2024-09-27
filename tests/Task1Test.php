<?php

use PHPUnit\Framework\TestCase;

class Task1Test extends TestCase
{
    public function testSimple()
    {
        $text = 'some text[TAG_NAME:description]data[/TAG_NAME]some more text';
        $expected = [
            'TAG_NAME' => [
                'description' => 'description',
                'data' => 'data'
            ]
        ];
        $this->assertEquals($expected, Task1::parse($text));
    }

    public function testMultipleTags()
    {
        $text = '[TAG_NAME1:description1]data1[/TAG_NAME1]some text[TAG_NAME2:description2]data2[/TAG_NAME2]';
        $expected = [
            'TAG_NAME1' => [
                'description' => 'description1',
                'data' => 'data1'
            ],
            'TAG_NAME2' => [
                'description' => 'description2',
                'data' => 'data2'
            ]
        ];
        $this->assertEquals($expected, Task1::parse($text));
    }

    /**
     * There is no info in the task about what the description it might be but let's assume that it might be multiline text
     */
    public function testComplicatedDescription()
    {
        $text = '[TAG_NAME:<![CDATA[description
        with
        multi rows]]>]some 
        multirow 
        data[/TAG_NAME]some text';
        $expected = [
            'TAG_NAME' => [
                'description' => '<![CDATA[description
        with
        multi rows]]>',
                'data' => 'some 
        multirow 
        data'
            ]
        ];
        $this->assertEquals($expected, Task1::parse($text));
    }

    public function testNoData()
    {
        $text = '[TAG_NAME:description][/TAG_NAME]';
        $expected = [
            'TAG_NAME' => [
                'description' => 'description',
                'data' => ''
            ]
        ];
        $this->assertEquals($expected, Task1::parse($text));
    }

    public function testNoDescription()
    {
        $text = '[TAG_NAME]data[/TAG_NAME]';
        $expected = [
            'TAG_NAME' => [
                'description' => '',
                'data' => 'data'
            ]
        ];
        $this->assertEquals($expected, Task1::parse($text));
    }

    public function testNoTags()
    {
        $text = 'Just some text';
        $expected = [];
        $this->assertEquals($expected, Task1::parse($text));
    }

    public function testNoCloseTag()
    {
        $text = '[TAG_NAME:descriptiondata[/TAG_NAME]';
        $expected = [];
        $this->assertEquals($expected, Task1::parse($text));
    }

    public function testInvalidTagFormat()
    {
        $text = '[TAG 1:description]data[/TAG 1]';
        $expected = [];
        $this->assertEquals($expected, Task1::parse($text));
    }
}