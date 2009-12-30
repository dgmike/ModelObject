<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker: */

require_once('Interpretor.php');

class Model_Object
{
    public $_con = false;
    public $_interoretor = false;

    public function __construct($dns, $user=null, $pass=null)
    {
        $this->_interpretor = $this->getInterpretor($dns);
        $this->_con  = new PDO($dns, $user, $pass);
    }
    
    public function getInterpretor ($dns)
    {
        $interpretor = 'interpretor_'.reset(explode(':', $dns));
        $interpretor = str_replace('_', ' ', $interpretor);
        $interpretor = ucwords(strtolower($interpretor));
        $interpretor = str_replace(' ', DIRECTORY_SEPARATOR, $interpretor);
        // get the file
        $file        = realpath(dirname(__FILE__));
        $file       .= DIRECTORY_SEPARATOR.$interpretor.'.php';
        if (!file_exists($file)) {
            throw new Exception ('Interpretor file not found: '.$file);
        }
        require_once($file);
        // Sets the interepretor
        $interpretor = 'Model_'
                       .str_replace(DIRECTORY_SEPARATOR, '_', $interpretor);
        if (!class_exists($interpretor)) {
            throw new Exception ('Interpretor class not found: '.$interpretor);
        }
        return new $interpretor;
    }
}
