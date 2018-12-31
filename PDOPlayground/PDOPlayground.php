<?php

use DarlingCms\classes\database\SQL\MySqlQuery;
use DarlingCms\classes\user\UserCrud;

// DEV CONSTANTS | Passwords to validate
define('PDO_PLAY_DEV_USER_NAME1', 'sevidmusic');
define('PDO_PLAY_DEV_USER_PASS1', 'ILoveDorianAndJamieEternally');
define('PDO_PLAY_DEV_USER_NAME2', 'DorianAlexanderDarling');
define('PDO_PLAY_DEV_USER_PASS2', 'iLoveMomAndDadEternally');
define('PDO_PLAY_DEV_USER_NAME3', 'JamieLynneDarling');
define('PDO_PLAY_DEV_USER_PASS3', 'iLoveSeviAndDorianEternally');

$dsn = MySqlQuery::getDsn('localhost', 'PDOPlaygroundDev1', MySqlQuery::DEFAULT_CHARSET);
$dbUser = 'root';
$dbUserPw = 'root';
try {
    $MySqlQuery = new MySqlQuery($dsn, $dbUser, $dbUserPw, MySqlQuery::DEFAULT_OPTIONS);
} catch
(\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
$actionCrud = new \DarlingCms\classes\crud\MySqlActionCrud($MySqlQuery);
$permissionCrud = new \DarlingCms\classes\crud\MySqlPermissionCrud($MySqlQuery, $actionCrud);
$roleCrud = new \DarlingCms\classes\crud\MySqlRoleCrud($MySqlQuery, $permissionCrud);
$userCrud = new \DarlingCms\classes\crud\MySqlUserCrud($MySqlQuery, $roleCrud);
$passwordCrud = new \DarlingCms\classes\crud\MySqlUserPasswordCrud($MySqlQuery);
// formatting/style
$ser = ['(', ')', '[', '=>'];
$rep = ['(<br>', '<br>)', '<br><span style="margin-right:30px;"></span>[', '<span style="margin: 0 20px; color: #0c2b62;">=></span>'];
$style = 'color: #83F52C; width:80%; margin: 10px auto;height: 200px; overflow: auto;background: #040e21;padding: 20px;';

// include views
include 'views/MySqlRolesCrud.php';
include 'views/MySqlUserCrud.php';
