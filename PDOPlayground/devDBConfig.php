<?php
/**
 * Create a new Data Base the PDO way.
 * Source for boilerplate @see https://stackoverflow.com/questions/2583707/can-i-create-a-database-using-pdo-in-php
 * WARNING: THE FOLLOWING CODE IS FOR LOCAL DEV ONLY! DO NOT USE IN ANY PRODUCTION SITE!!!
 */
$host = "localhost";
$root = "root";
$root_password = "root";
$user = 'sevidmusic';
$pass = 'iLoveDorianEternally';
$db = "PDOPlaygroundDev1";
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
