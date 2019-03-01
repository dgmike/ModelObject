<?php

namespace Test\Model;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    private function instance(string $dns = 'sqlite::memory:'): \Model\Configuration
    {
        return new \Model\Configuration($dns);
    }

    public function testConfigurationClassExists(): void
    {
        $this->assertInstanceOf(
            \Model\Configuration::class,
            $this->instance()
        );
    }

    public function testConfigurationSimpleSignature(): void
    {
        $instance = $this->instance();

        $this->assertEquals('sqlite::memory:', $instance->getDns());
        $this->assertEquals(null, $instance->getUsername());
        $this->assertEquals(null, $instance->getPassword());
        $this->assertEquals(null, $instance->getOptions());
    }

    public function testConfigurationFullSignature(): void
    {
        $instance = new \Model\Configuration(
            'mysql:host=local.host;port=3306;dbname=cms;charset=utf8',
            'username',
            'password',
            [
                \PDO::ATTR_TIMEOUT => 2,
            ]
        );

        $this->assertEquals('mysql:host=local.host;port=3306;dbname=cms;charset=utf8', $instance->getDns());
        $this->assertEquals('username', $instance->getUsername());
        $this->assertEquals('password', $instance->getPassword());
        $this->assertEquals([\PDO::ATTR_TIMEOUT => 2], $instance->getOptions());
    }
}
