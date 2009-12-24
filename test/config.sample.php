<?php
set_include_path(realpath('..').PATH_SEPARATOR.get_include_path());

define('TEST_MYSQL_DATAHOST', 'localhost');
define('TEST_MYSQL_DATANAME', 'ice');
define('TEST_MYSQL_USERNAME', 'ice');
define('TEST_MYSQL_PASSWORD', 'ice');

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
