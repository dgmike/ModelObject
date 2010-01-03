<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker: */

/**
 * Model_Object
 *
 * Copyright (c) 2009-2010, Michael Granados <michael@pontovermelho.com.br>.
 *
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Sebastian Bergmann nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Database Mapper
 * @package    Model Object
 * @author     Michael Granados <michael@pontovermelho.com.br>
 * @copyright  2009-2010 Michael Granados <michael@pontovermelho.com.br>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.0
 */

require_once('Interpretor.php');

/**
 * Model_Object
 *
 * @category   Database Mapper
 * @package    Model Object
 * @author     Michael Granados <michael@pontovermelho.com.br>
 * @copyright  2009-2010 Michael Granados <michael@pontovermelho.com.br>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.0
 */
class Model_Object
{
    /**
     * The stored config for connect on database. @see Model_Object::store
     *
     * @var    array
     */
    static $_stored      = array();

    /**
     * The connection, you can use it to run your sql
     *
     * @var    PDO Object
     */
    public $_con         = false;

    /**
     * Interpretor to diferents databases
     *
     * @var    Model Interpretor Object
     */
    public $_interpretor = false;

    /**
     * Name of stored connection to use by default @see Model_Object::store
     *
     * @var    string
     */
    public $conection    = false;
    
    /**
     * Table manipulated by this object
     *
     * @var    string
     */

    public $table        = null;

    /**
     * Key column used to manipulate table. Normally primary key
     *
     * @var    string
     */
    public $key          = null;


    /**
     * Creates a new Model Object to manipulate a table
     *
     * On create you can use any of these methods.
     *
     * Same as PDO @see PDO::__construct()
     *
     * <code>
     * <?php
     * $con = new Model_Object('sqlite:database.db');
     * $con = new Model_Object('mysql:host=localhost;dbname=ice','user','pass');
     * </code>
     *
     * Use stored connection @see Model_Object::store()
     *
     * <code>
     * <?php
     * Model_Object::store(array(
     *     'con1' => array(
     *                   'dns' => 'sqlite:database.db',
     *               ),
     *     'con2' => array(
     *                   'dns'  => 'mysql:host=localhost;dbname=ice',
     *                   'user' => 'user',
     *                   'pass' => 'pass',
     *               ),
     * ));
     * $con1 = new Model_Object('con1');
     * $con2 = new Model_Object('con2');
     * </code>
     *
     * Use constants
     *
     * <code>
     * define('DATABASE_DNS',  'mysql:host=localhost;dbname=ice');
     * define('DATABASE_USER', 'user');
     * define('DATABASE_PASS', 'pass');
     * $con = new Model_Object;
     * </code>
     *
     * Use extended Object whit pre-defined stored connection
     * @see Model_Object::store()
     *
     * <code>
     * <?php
     * class Person {
     *     public $connection = 'con1';
     * }
     *
     * class Phone {
     *     public $connection = 'con2';
     * }
     *
     * Model_Object::store(array(
     *     'con1' => array(
     *                   'dns' => 'sqlite:database.db',
     *               ),
     *     'con2' => array(
     *                   'dns'  => 'mysql:host=localhost;dbname=ice',
     *                   'user' => 'user',
     *                   'pass' => 'pass',
     *               ),
     * ));
     * $con1 = new Person;
     * $con2 = new Phone;
     * </code>
     *
     * @use Model_Object::setKey()
     * @use Model_Object::setTable()
     *
     * @param string $dns
     * @param string $user
     * @param string $pass
     */
    public function __construct($dns=null, $user=null, $pass=null)
    {
        if (!$dns AND $this->connection) {
            $dns = $this->connection;
        }
        if ($dns and strpos($dns, ':') === false) {
            if (!isset(self::$_stored[$dns])) {
                throw new Exception ('Invalid stored connection: '.$dns);
            }
            $connection = self::$_stored[$dns] +
                          array('dns' => null, 'user' => null, 'pass' => null);
            foreach (array( 'dns', 'user', 'pass' ) as $item) {
                $$item = $connection[$item];
            }
        }
        foreach (array( 'dns', 'user', 'pass' ) as $item) {
            if (!$$item AND defined('DATABASE_'.strtoupper($item))) {
                $$item = constant('DATABASE_'.strtoupper($item));
            }
        }
        $this->_interpretor = $this->getInterpretor($dns);
        $this->_con  = new PDO($dns, $user, $pass);
        
        $this->setTable();
        $this->setKey();
    }

    /**
     * Sets the table to manipulate with this object
     *
     * @param  string $table The table name (optional)
     * @return string
     */
    public function setTable($table = null)
    {
        if (!$this->table) {
            if (!$table) {
                $object = get_class($this);
                $table  = strtolower($object);
            }
            $this->table = $table;
        }
        return $this->table;
    }

    /**
     * Sets the key to manipulate the table with this object
     *
     * @param  string $key The key to manipulate the table (optional)
     * @return string
     */
    public function setKey($key = null)
    {
        if (!$this->key) {
            if (!$key) {
                $object = get_class($this);
                $key    = 'id_'.strtolower($object);
            }
            $this->key = $object;
        }
        return $this->key;
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

    /**
     * Stores news connections settings to easy use to connect on server.
     * @see Model_Object::__construct()
     *
     * You need to pass an assossiative array with your settings where 
     * the key is the name you use to connect and the settings are in the
     * value. The value is an array assossiative with dns required, user
     * and password optional.
     *
     * With stored connections you can easy connect to your database using
     * the stored name or creating a extended object with connection setting
     * pointing to stored name.
     *
     * <code>
     * <?php
     * Model_Object::store(array(
     *     'con1' => array(
     *                   'dns' => 'sqlite:database.db',
     *               ),
     *     'con2' => array(
     *                   'dns'  => 'mysql:host=localhost;dbname=ice',
     *                   'user' => 'user',
     *                   'pass' => 'pass',
     *               ),
     * ));
     * $con1 = new Model_Object('con1');
     * $con2 = new Model_Object('con2');
     * </code>
     *
     * <code>
     * <?php
     * class Person {
     *     public $connection = 'con1';
     * }
     *
     * class Phone {
     *     public $connection = 'con2';
     * }
     *
     * Model_Object::store(array(
     *     'con1' => array(
     *                   'dns' => 'sqlite:database.db',
     *               ),
     *     'con2' => array(
     *                   'dns'  => 'mysql:host=localhost;dbname=ice',
     *                   'user' => 'user',
     *                   'pass' => 'pass',
     *               ),
     * ));
     * $con1 = new Person;
     * $con2 = new Phone;
     * </code>
     *
     * @param array $connections An assossiative array with settings
     * @return bool
     */
    static function store (array $connections = array())
    {
        self::$_stored = $connections + self::$_stored;
        return true;
    }

    public function get($id, $table = null, $key = null)
    {
        foreach (array('table', 'key') as $item) {
            if (!$$item) {
                $$item = $this->$item;
            }
        }
        $sql = $this->_interpretor->get($id, $key, $table);
        $sth = $this->_con->prepare($sql);
        $sth->execute();
        return $sth->fetch(PDO::FETCH_OBJ);
    }
}