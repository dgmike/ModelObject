<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker: */

# A biblioteca PHPUnit deve estar instalada para rodar os testes

set_include_path(dirname(__FILE__).PATH_SEPARATOR.get_include_path());

require_once('config.php');
require_once('model/Object.php');
require_once('PHPUnit/Framework.php');

class ConnectTest extends PHPUnit_Framework_TestCase
{
    public $connections;

    public function setUp()
    {
        $this->connections = array(
                'default' => array(
                    'dns'  => 'sqlite:banco.db',
                ),
                'extra' => array(
                    'dns'  => $this->_dns('mysql'),
                    'user' => TEST_MYSQL_USERNAME,
                    'pass' => TEST_MYSQL_PASSWORD,
                ),
            );

        if (file_exists('banco.db')) {
            unlink('banco.db');
        }
        $con = new PDO('sqlite:banco.db');
        $sql = file_get_contents('banco.sql');
        foreach (explode(';', $sql) as $instruction) {
            if (!trim($instruction)) {
                continue;
            }
            $con->exec($instruction);
        }
    }

    public function tearDown()
    {
        if (file_exists('banco.db')) {
            unlink('banco.db');
        }
    }

    public function _dns($type)
    {
        if ($type == 'sqlite')
            return 'sqlite:banco.db';
        if ($type == 'mysql')
            return sprintf('mysql:host=%s;dbname=%s', TEST_MYSQL_DATAHOST, TEST_MYSQL_DATANAME);
    }

    public function testSQLite()
    {
        $con = new Model_Object($this->_dns('sqlite'));
        $this->assertFileExists('banco.db');
        $this->assertEquals('object', gettype($con->_con));
        $this->assertEquals('PDO', get_class($con->_con));
    }

    public function testMySQL()
    {
        $con = new Model_Object($this->_dns('mysql'), TEST_MYSQL_USERNAME, TEST_MYSQL_PASSWORD);
        $this->assertEquals('object', gettype($con->_con));
        $this->assertEquals('PDO', get_class($con->_con));
    }

    public function testInterpretor()
    {
        $con = new Model_Object($this->_dns('mysql'), TEST_MYSQL_USERNAME, TEST_MYSQL_PASSWORD);
        $this->assertEquals('Model_Interpretor_Mysql', get_class($con->_interpretor));
        $con2 = new Model_Object($this->_dns('sqlite'));
        $this->assertEquals('Model_Interpretor_Sqlite', get_class($con2->_interpretor));
    }

    public function testNotInterpretor ()
    {
        $file = realpath('../model/Interpretor'.DIRECTORY_SEPARATOR.'invalidDriver.php');
        $this->setExpectedException('Exception');
        $con = new Model_Object('invalidDriver:localhost', 'username', 'password');
    }
    
    public function testConnectDefined ()
    {
        $con = new Model_Object;
        $this->assertEquals('Model_Interpretor_Sqlite', get_class($con->_interpretor));
        $this->assertFileExists('banco.db');
    }

    public function testStoredConnection()
    {
        $this->assertTrue(Model_Object::store($this->connections));
        $con = new Model_Object('default');
        $this->assertFileExists('banco.db');
        $this->assertEquals('Model_Interpretor_Sqlite', get_class($con->_interpretor));

        $con2 = new Model_Object('extra');
        $this->assertEquals('Model_Interpretor_Mysql', get_class($con2->_interpretor));
    }

    public function testStoredInvalidConnection()
    {
        $this->assertTrue(Model_Object::store($this->connections));
        $this->setExpectedException('Exception');
        $con = new Model_Object('invalid');
    }

    public function testConfigInObject()
    {
        $this->assertTrue(Model_Object::store($this->connections));
        $m_pessoa = new Pessoa;
        $this->assertEquals('Model_Interpretor_Mysql', get_class($m_pessoa->_interpretor));
    }
}

if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    rodaTest('ConnectTest');
}