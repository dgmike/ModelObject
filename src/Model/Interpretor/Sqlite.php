<?php

namespace Model\Interpretor;

use Model\InterpretorInterface;

class Sqlite implements InterpretorInterface
{
    public function get ($id, $key, $table): String
    {
        return sprintf('SELECT * FROM %s WHERE %s = %s',
            $table, $key, $id
        );
    }

    public function select ()
    {}
}
