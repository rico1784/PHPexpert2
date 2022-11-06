<?php

class dbconnect
{

    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $dbname = 'reshotels';

    protected function connect()
    {
        $dsn = 'mysql:host  =' . $this->host . ';dbname=' . $this->dbname;
        $pdo = new PDO($dsn, $this->user, $this->pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }


}
