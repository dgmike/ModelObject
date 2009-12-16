<?php

class Model_Object
{
    public $_con = false;

    public function __construct($dns)
    {
        $this->_con = new PDO($dns);
    }
}
