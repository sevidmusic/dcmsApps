<?php
// @todo Create methods in a DCMS Utility class for require, include, etc. Should be able to do DCMS::include($filename, DCMS::PATH_CONSTANT).*EOL*This would help alliviate the tedious task of figuring out coreect paths manually, and would allow files to be moved around and still be able to rely on the include methods knowing the correct path to the requested file.
require 'functions.php';
require 'devDBConfig.php';
// attempt to connect
try {
    $dbCrud = new devPdoCrud($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
/** // Getting single row
 * //$userId1 = $dbCrud->runQuery("SELECT userId FROM users WHERE userName=?", ['sevidmusic'])->fetch();
 * // Getting single column from the single row
 * //$userId2 = $dbCrud->runQuery("SELECT userId FROM users WHERE userName=?", ['dorianDarling'])->fetchColumn();
 * // Getting array of rows and populating an instance of a specified class with the returned data.
 */
$userClasses = $dbCrud->runQuery("SELECT * FROM users LIMIT ?", ['14'])->fetchAll(PDO::FETCH_CLASS, 'devUser');
// Display each user's information.
foreach ($userClasses as $user) {
    echo "<p>User Name: {$user->getUserName()}</p>";
    echo "<p>User Id: {$user->getUserId()}</p>";
    echo "<p>User Password: " . password_hash($dbCrud->runQuery("SELECT password FROM passwords WHERE userId=?", [$user->getUserId()])->fetch()['password'], PASSWORD_DEFAULT) . "</p>";
}
