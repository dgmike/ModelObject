<?php

namespace Model\Interpretor;

use Model\InterpretorInterface;

class Mysql implements InterpretorInterface
{
    public function get ($id, $key, $table): String
    {
        return sprintf('SELECT * FROM `%s` WHERE `%s` = %s',
            $table, $key, $id
        );
    }

    function select() { return true; }
}
