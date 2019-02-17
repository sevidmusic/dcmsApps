<?php

use DarlingCms\classes\user\User;

if (filter_input(INPUT_POST, 'ajaxRequest') === 'true') {
    require str_replace('/apps/UserManager/handlers', '/vendor/autoload.php', __DIR__);
}

$crudFactory = new \DarlingCms\classes\factory\CoreMySqlCrudFactory();
$actionCrud = $crudFactory->getActionCrud();
$permissionCrud = $crudFactory->getPermissionCrud();
$roleCrud = $crudFactory->getRoleCrud();
$userCrud = $crudFactory->getUserCrud();
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
$metaData = array_map(function ($array) {
    foreach ($array as $key => $value) {
        if (empty($key) === true) {
            unset($array[$key]);
        }
    }
    return array_filter($array, function ($value, $key) {
        return ($key !== null && $key !== false && $key !== '' && $value !== null && $value !== false && $value !== '');
    }, ARRAY_FILTER_USE_BOTH);
}, $metaData);
$newUser = new User($post['userName'], $metaData, $assignedRoles);
if ($userCrud->update($post['originalUserName'], $newUser) === true) {
    echo '<p class="dcms-positive-text">Updated user ' . $post['originalUserName'] . ' successfully...</p>';
} else {
    echo '<p class="dcms-negative-text">The user ' . $post['originalUserName'] . ' could not be updated. Please try again...</p>';
}

