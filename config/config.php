<?php

define('MYSQL_HOST', 'localhost');
define('MYSQL_DATABASE', 'blog');
define('MYSQL_USER', 'root');
define('MYSQL_PASSWORD', '');

$option = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
);

$pdo = new PDO(
    'mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DATABASE,
    MYSQL_USER,
    MYSQL_PASSWORD,
    $option
);
