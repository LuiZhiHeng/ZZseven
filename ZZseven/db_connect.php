<?php //db_connect.php
    function db_connect(){
        $hn = 'localhost';
        $un = 'root';
        $pw = '';
        $db = 'zzseven';
        $conn = new mysqli($hn, $un, $pw, $db);
        if (!$conn) throw new Exception('Cound not connect to database server');
        else return $conn;
    }
?>