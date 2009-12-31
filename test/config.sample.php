<?php
set_include_path(realpath('..').PATH_SEPARATOR.get_include_path());

define('TEST_MYSQL_DATAHOST', 'localhost');
define('TEST_MYSQL_DATANAME', 'ice');
define('TEST_MYSQL_USERNAME', 'ice');
define('TEST_MYSQL_PASSWORD', 'ice');

define('DATABASE_DNS',      'sqlite:banco.db');
define('DATABASE_USERNAME', '');
define('DATABASE_PASSWORD', '');

require_once('Model/Object.php');

class Pessoa extends Model_Object
{
    public $connection = 'extra';
}

function rodaTest($class)
{
    if (count($_SERVER['argv'])) {
        require_once('PHPUnit/Framework.php');
        require_once('PHPUnit/TextUI/ResultPrinter.php');

        $suite = new PHPUnit_Framework_TestSuite();
        $suite->addTestSuite($class);
        $result = $suite->run();
        $reporter = new PHPUnit_TextUI_ResultPrinter;
        $reporter->printResult($result);
    }
}

function _dns($type)
{
    if ($type == 'sqlite')
        return 'sqlite:banco.db';
    if ($type == 'mysql')
        return sprintf('mysql:host=%s;dbname=%s', TEST_MYSQL_DATAHOST, 
                       TEST_MYSQL_DATANAME);
}

function _runSql()
{
    $con = new PDO(_dns('sqlite'));
    $sql = file_get_contents('banco.sql');
    foreach (explode(';', $sql) as $instruction) {
        if (!trim($instruction)) {
            continue;
        }
        $con->exec($instruction);
    }
}

function _connections()
{
    return array(
            'default' => array(
                'dns'  => _dns('sqlite'),
            ),
            'extra' => array(
                'dns'  => _dns('mysql'),
                'user' => TEST_MYSQL_USERNAME,
                'pass' => TEST_MYSQL_PASSWORD,
            ),
        );
}