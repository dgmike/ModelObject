<?php

class Model_Object
{
    public $_con = false;

    public function __construct($dns, $user=null, $pass=null)
    {
        $this->_con = new PDO($dns, $user, $pass);
    }
}
