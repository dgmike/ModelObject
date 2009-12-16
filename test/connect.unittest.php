<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker: */

# A biblioteca PHPUnit deve estar instalada para rodar os testes
require_once('config.php');
require_once('model/Object.php');
require_once('PHPUnit/Framework.php');

class ConnectTest extends PHPUnit_Framework_TestCase
{
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
}

if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    rodaTest('ConnectTest');
}

