<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker: */

# A biblioteca PHPUnit deve estar instalada para rodar os testes

set_include_path(dirname(__FILE__).PATH_SEPARATOR.get_include_path());

require_once('config.php');
require_once('model/Object.php');
require_once('PHPUnit/Framework.php');

class ConnectTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (file_exists('banco.db')) {
            unlink('banco.db');
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
        /*
        $con = new Model_Object($this->_dns('mysql'), TEST_MYSQL_USERNAME, TEST_MYSQL_PASSWORD);
        $this->assertEquals('Model_Interpretor_Mysql', get_class($con->_interpretor));
        $con2 = new Model_Object($this->_dns('sqlite'));
        $this->assertEquals('Model_Interpretor_Sqlite', get_class($con2->_interpretor));
        */
    }

    public function testNotInterpretor ()
    {
        $ar = (get_class_methods($this)); sort($ar); print join("\n", $ar);
        $file = realpath('../model/Interpretor'.DIRECTORY_SEPARATOR.'invalidDriver.php');
        $this->setExpectedException('Interpretor file not found: '.$file);
        $con = new Model_Object('invalidDriver:localhost', 'username', 'password');
    }
}

if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    rodaTest('ConnectTest');
}