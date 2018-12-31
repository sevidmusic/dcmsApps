<?php
/**
 * NOTE: MySqlQuery and UserCrud instantiated in PDOPlayground.php. Since this file is included by
 * that file those vars will be available to this file as $MySqlQuery and $UserCrud, respectively.
 */

use DarlingCms\classes\user\User;

$userConfig = array(
    PDO_PLAY_DEV_USER_NAME1 => PDO_PLAY_DEV_USER_PASS1,
    PDO_PLAY_DEV_USER_NAME2 => PDO_PLAY_DEV_USER_PASS2,
    PDO_PLAY_DEV_USER_NAME3 => PDO_PLAY_DEV_USER_PASS3,
);
foreach ($userConfig as $username => $password) {
    $devUserName = $username;
    $devUserPass = $password;
    $devUserMeta = [
        User::USER_PUBLIC_META_INDEX => array(
            'email' => $username . '@DarlingCms.net',
            'phone' => '(518)-989-6692',
            'ext' => strval(rand(1000, 9999)),
        ),
        User::USER_PRIVATE_META_INDEX => array(
            'email' => $username . '@gmail.com',
            'homePhone' => '(518)-989-66' . strval(rand(20, 99))
        )
    ];
    $devUser = new User(
        $devUserName,
        $devUserMeta,
        new \DarlingCms\classes\privilege\Role(
            'Basic User',
            new \DarlingCms\classes\privilege\Permission(
                'View Public',
                new \DarlingCms\classes\privilege\Action('Read Public', 'Read all public content.')
            ),
            new \DarlingCms\classes\privilege\Permission(
                'Own Crud',
                new \DarlingCms\classes\privilege\Action('Create Own', 'Create all content owned by user.'),
                new \DarlingCms\classes\privilege\Action('Read Own', 'Read all content owned by user.'),
                new \DarlingCms\classes\privilege\Action('Update Own', 'Update all content owned by user.'),
                new \DarlingCms\classes\privilege\Action('Delete Own', 'Delete all content owned by user.')
            )
        )
    );
    $devUserPassword = new \DarlingCms\classes\user\UserPassword($devUser, $devUserPass);
    if ($userCrud->create($devUser, $devUserPassword) === true) {
        echo '<p class="dcms-positive-text">Created new user ' . $devUser->getUserName() . '</p>';
        $usr = $userCrud->read($devUserName);
        if (get_class() !== 'DarlingCms\classes\user\AnonymousUser') {
            echo '<p class="dcms-positive-text">User Name: ' . $usr->getUserName() . '</p>';
            echo '<p class="dcms-positive-text">User Id: ' . $usr->getUserId() . '</p>';
            echo '<p class="dcms-positive-text">User Public Meta: ' . implode('<br>- ', $usr->getPublicMeta()) . '</p>';
            echo '<p class="dcms-positive-text">User Private Meta: ' . implode('<br>- ', $usr->getPrivateMeta()) . '</p>';
        }
    }

}
