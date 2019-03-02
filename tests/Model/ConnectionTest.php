<?php

namespace Test\Model;
use PHPUnit\Framework\TestCase;
use Model\Configuration;
use Model\Connection;

class ConnectionTest extends TestCase
{
    public function testAddConfiguration(): void
    {
        $configuration = new Configuration('sqlite::memory:');
        Connection::addConfiguration('main', $configuration);

        $this->assertEquals(Connection::getConfiguration('main'), $configuration);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Configruation "invalid" is not defined
     */
    public function testExpectTrowOnUndefinedConfiguration(): void
    {
        Connection::getConfiguration('invalid');
    }

    public function testSetConfiguration(): void
    {
        $configuration1 = new Configuration('sqlite::memory:');
        $configuration2 = new Configuration('sqlite::memory:');

        $configurations = [
            'read' => $configuration1,
            'write' => $configuration2,
        ];
        Connection::setConfiguration($configurations);

        $this->assertEquals(Connection::getConfiguration('read'), $configuration1);
        $this->assertEquals(Connection::getConfiguration('write'), $configuration2);
    }
}
