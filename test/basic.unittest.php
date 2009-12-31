<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker: */

# A biblioteca PHPUnit deve estar instalada para rodar os testes

set_include_path(dirname(__FILE__).PATH_SEPARATOR.get_include_path());

require_once('config.php');
require_once('PHPUnit/Framework.php');

Model_Object::store(_connections());

class BasicTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (file_exists('banco.db')) {
            unlink('banco.db');
        }
        _runSql();
    }

    public function tearDown()
    {
        if (file_exists('banco.db')) {
            unlink('banco.db');
        }
    }

    public function pessoaTest($id, $nome, $idade)
    {
        $m_pessoa = new Pessoa;
        $pessoa = $m_pessoa->get($id);
        $this->assertEquals($nome, $pessoa->nome);
        $this->assertEquals($idade, $pessoa->idade);
    }

    public function testGet()
    {
        $data = array(
            array(1, 'Alice', 26),
            array(2, 'Michael', 25),
            array(3, 'Rafael', 25),
            array(4, 'Gustavo', 27),
            array(5, 'Jessica', 22),
            array(6, 'Erica', 28),
        );
        foreach( $data as $item) {
            $this->pessoaTest ($item[0], $item[1], $item[2]);
        }
    }
}

if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    rodaTest('BasicTest');
}