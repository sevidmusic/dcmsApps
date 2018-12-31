<?php

$action = new \DarlingCms\classes\privilege\Action('Read Public', 'View public site content');
$permission = new \DarlingCms\classes\privilege\Permission('View Public Content', $action);
$role = new \DarlingCms\classes\privilege\Role('Basic User', $permission);
$user1 = new \DarlingCms\classes\user\User('sevidmusic', array(\DarlingCms\classes\user\User::USER_PUBLIC_META_INDEX => array('email' => 'sdmwebsdm@gmail.com', 'phone' => '(518)-989-6692'), \DarlingCms\classes\user\User::USER_PRIVATE_META_INDEX => array('cell' => '(615)-856-0448', 'email' => 'sevidmusic@gmail.com')), $role);
$user2 = new \DarlingCms\classes\user\User('dorianAlexanderDarling', array(\DarlingCms\classes\user\User::USER_PUBLIC_META_INDEX => array('email' => 'sdmwebsdm@gmail.com', 'phone' => '(518)-989-6692'), \DarlingCms\classes\user\User::USER_PRIVATE_META_INDEX => array('cell' => '(615)-856-0448', 'email' => 'dorianDarling@gmail.com')), $role);
$user3 = new \DarlingCms\classes\user\User('JamieLynneDarling', array(\DarlingCms\classes\user\User::USER_PUBLIC_META_INDEX => array('email' => 'sdmwebsdm@gmail.com', 'phone' => '(518)-989-6692'), \DarlingCms\classes\user\User::USER_PRIVATE_META_INDEX => array('cell' => '(615)-856-0448', 'email' => 'satelliteqt@gmail.com>')), $role);
$user1Pass = 'iLoveDorianForever';
$user2Pass = 'iLoveMomAndDadForever';
$user3Pass = 'iLoveDorianAndSeviForever';
$user1PW = new \DarlingCms\classes\user\UserPassword($user1, $user1Pass);
$user2PW = new \DarlingCms\classes\user\UserPassword($user2, $user2Pass);
$user3PW = new \DarlingCms\classes\user\UserPassword($user3, $user3Pass);
$userClasses = array($user1, $user2, $user3);
$incrementer = 1;
// Display information for each user.
foreach ($userClasses as $user) {
    $userPass = 'user' . strval($incrementer) . 'Pass';
    $userPW = 'user' . strval($incrementer) . 'PW';
    echo '<div style="background: #0b151c;padding:20px;margin: 20px;">';
    echo "<p>Processing user{$incrementer}</p>";
    echo "<p>User Name: {$user->getUserName()}</p>";
    echo "<p>User Id: {$user->getUserId()}</p>";
    echo "<p>Public Meta:<br>" . implode('<br>', $user->getPublicMeta()) . "</p>";
    echo "<p>Private Meta:<br>" . implode('<br>', $user->getPrivateMeta()) . "</p>";
    if (in_array('DarlingCms\interfaces\user\IUserPassword', class_implements($$userPW), true) && method_exists($$userPW, 'verifyPassword')) {
        echo "<p>Verifying password for user: {$user->getUserName()}</p>";
        echo "<p>Password is valid: " . ($$userPW->verifyPassword($user, $$userPass) === true ? 'True' : 'False') . "</p>";
    }
    echo '</div>';
    $incrementer++;
}
