<?php
function rodaTest($class)
{
    $suite  = new PHPUnit_Framework_TestSuite($class);
    $result = PHPUnit::run($suite);
    if (count($_SERVER['argv'])) {
        print PHP_EOL.$result->toString().PHP_EOL;
    } else {
        print $result->toHTML();
    }
}
