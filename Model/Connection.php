<?php


class Connection extends PDO
{
    public function __construct()
    {
        $database = include('config.php');
        $driverOptions = [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        parent::__construct('mysql:host=' . $database['dbhost'] . ';dbname=' . $database['db'], $database['dbuser'], $database['dbpass'], $driverOptions);
    }
}
