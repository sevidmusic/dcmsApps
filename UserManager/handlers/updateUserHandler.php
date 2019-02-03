<?php

use DarlingCms\classes\staticClasses\core\CoreValues;
use DarlingCms\classes\user\User;

if (filter_input(INPUT_POST, 'ajaxRequest') === 'true') {
    require str_replace('/apps/UserManager/handlers', '/vendor/autoload.php', __DIR__);
}

$sqlQuery = CoreValues::getISqlQueryInstance
(
    CoreValues::CORE_DB_HOST,
    CoreValues::CORE_DB_NAME,
    'root',
    'root'
);
$actionCrud = new \DarlingCms\classes\crud\MySqlActionCrud($sqlQuery);
$permissionCrud = new \DarlingCms\classes\crud\MySqlPermissionCrud($sqlQuery, $actionCrud);
$roleCrud = new \DarlingCms\classes\crud\MySqlRoleCrud($sqlQuery, $permissionCrud);
$userCrud = new \DarlingCms\classes\crud\MySqlUserCrud($sqlQuery, $roleCrud);
$post = filter_input_array(INPUT_POST);
$roles = array_merge(
    array_combine(
        (isset($post['assignedRoleNames']) ? $post['assignedRoleNames'] : array()),
        (isset($post['assignedRoleStates']) ? $post['assignedRoleStates'] : array())
    ),
    array_combine(
        (isset($post['availableRoleNames']) ? $post['availableRoleNames'] : array()),
        (isset($post['availableRoleStates']) ? $post['availableRoleStates'] : array())
    )
);
$assignedRoles = array();
foreach ($roles as $roleName => $roleState) {
    if ($roleState === 'true') {
        array_push($assignedRoles, $roleCrud->read($roleName));
    }
}
////
$metaData = array(
    User::USER_PUBLIC_META_INDEX => array_combine(
        (isset($post['publicMetaDataKeys']) ? $post['publicMetaDataKeys'] : array()),
        (isset($post['publicMetaDataValues']) ? $post['publicMetaDataValues'] : array())
    ),
    User::USER_PRIVATE_META_INDEX => array_combine(
        (isset($post['privateMetaDataKeys']) ? $post['privateMetaDataKeys'] : array()),
        (isset($post['privateMetaDataValues']) ? $post['privateMetaDataValues'] : array())
    )
);

$metaData = array_filter($metaData, function ($value) {
    if (is_array($value)) {
        $status = array();
        foreach ($value as $item) {
            array_push($status, !empty($item));
        }
    }
    return !empty($value) && !in_array(false, $status, true);
});
$newUser = new User($post['userName'], $metaData, $assignedRoles);
var_dump($newUser);
/*
if ($userCrud->update($post['originalUserName'], $newUser) === true) {
    echo '<p class="dcms-positive-text">Updated the ' . $post['originalUserName'] . ' User Successfully...</p>';
} else {
    echo '<p class="dcms-negative-text">The ' . $post['originalUserName'] . ' User could not be updated. Please try again...</p>';
}

*/
