<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker: */

abstract class Model_Interpretor
{
    abstract function get($id, $key, $table);

    abstract function select();
}