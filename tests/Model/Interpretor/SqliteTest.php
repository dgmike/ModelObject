<?php

namespace Test\Model\Interpretor;
use PHPUnit\Framework\TestCase;

/**
 * InterpretorTest
 */
class SqliteTest extends TestCase
{
    public function instance(): \Model\Interpretor\Sqlite
    {
        return new \Model\Interpretor\Sqlite;
    }

    public function testInterpretorClassExists(): void
    {
        $this->assertInstanceOf(
            \Model\InterpretorInterface::class,
            new \Model\Interpretor\Sqlite()
        );
    }

    public function testGetReturnsQueryByID(): void
    {
        $this->assertEquals(
            $this->instance()->get(1, 'ID', 'people'),
            'SELECT * FROM people WHERE ID = 1'
        );
    }
}
