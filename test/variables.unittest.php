<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker: */

# A biblioteca PHPUnit deve estar instalada para rodar os testes
require_once('config.php');
require_once('PHPUnit/Framework.php');

class VariablesTest extends PHPUnit_Framework_TestCase
{
}

if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    rodaTest('VariablesTest');
}
