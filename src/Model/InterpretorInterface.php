<?php

namespace Model;

interface InterpretorInterface
{
    public function get($id, $key, $table): String;
    public function select();
}
