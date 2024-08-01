<?php
    require_once('generales.php');

// --> Establecemos conexión con la BBDD
    // $db_attrs is optional, this demonstrates using persistent connections,
    // the equivalent of mysql_pconnect
    $db_attrs = array(PDO::ATTR_PERSISTENT => true);
    $dsn      = 'mysql:host=' . BD_HOST . ';dbname=' . BD_NAME . ';charset=utf8';
    // connect to PDO
    $pdo = new PDO($dsn,BD_USER,BD_PASS, $db_attrs);
    // the following tells PDO we want it to throw Exceptions for every error.
    // this is far more useful than the default mode of throwing php errors
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // prepare the statement. the place holders allow PDO to handle substituting the values, which also prevents SQL injection
