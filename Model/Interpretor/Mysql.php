<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker ff=unix: */

class Model_Interpretor_Mysql extends Model_Interpretor
{
    public function get ($id, $key, $table)
    {
        return sprintf('SELECT * FROM `%s` WHERE `%s` = %s',
            $table, $key, $id
        );
    }

    function select() { return true; }
}
