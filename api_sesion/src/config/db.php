<?php

class db{

    private $host='localhost';
    private $user='root';
    private $password='8899';
    private $database='proyecto_ciisa';

    public function connect(){
        $conection_mysql="mysql:host=$this->host;dbname=$this->database";
        $conectionDB= new PDO($conection_mysql, $this->user,$this->password);
        $conectionDB->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $conectionDB->exec("set names utf8");
        return $conectionDB;
    }
}

 ?>