<?php 
/*This file will holds proxy connections to a database */

function mbtConnect() {
    $server = 'mysql';
    $dbname = 'dbase';
    $username = 'dbuser';
    $password = 'dbpass';
    $dsn = "mysql:host=$server;dbname=$dbname";
    $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);

    try {
        $link = new PDO($dsn, $username, $password, $options);
        return $link;

       } catch(PDOException $e) {
        header('Location: /mbt/view/500.php');
        exit;
       }

}
?>