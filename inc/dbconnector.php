<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'anmeldung';
$table = 'event';
$dsn = 'mysql:host=' . $host . ';dbname=' . $database;
$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

$db = new PDO($dsn, $user, $password, $options);
$db -> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
