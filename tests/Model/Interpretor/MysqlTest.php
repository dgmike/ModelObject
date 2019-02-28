<?php

namespace Test\Model\Interpretor;
use PHPUnit\Framework\TestCase;

/**
 * InterpretorTest
 */
class MysqlTest extends TestCase
{
    public function instance(): \Model\Interpretor\Mysql
    {
        return new \Model\Interpretor\Mysql;
    }

    public function testInterpretorClassExists(): void
    {
        $this->assertInstanceOf(
            \Model\InterpretorInterface::class,
            new \Model\Interpretor\Mysql()
        );
    }

    public function testGetReturnsQueryByID(): void
    {
        $this->assertEquals(
            $this->instance()->get(1, 'ID', 'people'),
            'SELECT * FROM `people` WHERE `ID` = 1'
        );
    }
}
