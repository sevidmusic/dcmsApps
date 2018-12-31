<?php
ini_set('xdebug.var_display_max_depth', '-1');
ini_set('xdebug.var_display_max_children', '-1');
ini_set('xdebug.var_display_max_data', '-1');

use DarlingCms\classes\user\User;

$updatedUser = new User(
    PDO_PLAY_DEV_USER_NAME1,
    [
        User::USER_PUBLIC_META_INDEX => $userCrud->read(PDO_PLAY_DEV_USER_NAME1)->getPublicMeta(),
        User::USER_PRIVATE_META_INDEX => $userCrud->read(PDO_PLAY_DEV_USER_NAME1)->getPrivateMeta()
    ],
    new \DarlingCms\classes\privilege\Role(
        'Admin',
        new \DarlingCms\classes\privilege\Permission(
            'All Crud',
            new \DarlingCms\classes\privilege\Action('Create All', 'Create All Content'),
            new \DarlingCms\classes\privilege\Action('Read All', 'Read All Content'),
            new \DarlingCms\classes\privilege\Action('Update All', 'Update All Content'),
            new \DarlingCms\classes\privilege\Action('Delete All', 'Delete All Content')
        )
    )
);
$updatedPassword = new \DarlingCms\classes\user\UserPassword($updatedUser, PDO_PLAY_DEV_USER_PASS1);
echo '<h3>Update</h3>';
var_dump($userCrud->update(PDO_PLAY_DEV_USER_NAME1, PDO_PLAY_DEV_USER_PASS1, $updatedUser, $updatedPassword));
