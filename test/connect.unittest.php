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

    public function testSQLite()
    {
        $con = new Model_Object('sqlite:banco.db');
        $this->assertFileExists('banco.db');
    }

    public function testMySQL()
    {
        $dns = sprintf('mysql:host=%s;dbname=%s', TEST_MYSQL_DATAHOST, TEST_MYSQL_DATANAME);
        $con = new Model_Object($dns, TEST_MYSQL_USERNAME, TEST_MYSQL_PASSWORD);
    }
}

if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    rodaTest('ConnectTest');
}

