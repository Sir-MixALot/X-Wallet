<?php

namespace app\vendor;

use app\lib\Db;

abstract class Model
{

    public $db;

    public function __construct(){
        $this->db = new Db;
    }

}