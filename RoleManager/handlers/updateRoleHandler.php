<?php

use \DarlingCms\classes\staticClasses\core\CoreValues;

if (filter_input(INPUT_POST, 'ajaxRequest') === 'true') {
    require str_replace('/apps/RoleManager/handlers', '/vendor/autoload.php', __DIR__);
}

$crudFactory = new \DarlingCms\classes\factory\CoreMySqlCrudFactory();
$actionCrud = $crudFactory->getActionCrud();
$permissionCrud = $crudFactory->getPermissionCrud();
$roleCrud = $crudFactory->getRoleCrud();

$post = filter_input_array(INPUT_POST);
$permissions = array_merge(
    array_combine(
        (isset($post['assignedPermissionNames']) ? $post['assignedPermissionNames'] : array()),
        (isset($post['assignedPermissionStates']) ? $post['assignedPermissionStates'] : array())
    ),
    array_combine(
        (isset($post['availablePermissionNames']) ? $post['availablePermissionNames'] : array()),
        (isset($post['availablePermissionStates']) ? $post['availablePermissionStates'] : array())
    )
);
$assignedPermissions = array();
foreach ($permissions as $permissionName => $permissionState) {
    if ($permissionState === 'true') {
        array_push($assignedPermissions, $permissionCrud->read($permissionName));
    }
}
$newRole = new \DarlingCms\classes\privilege\Role($post['roleName'], $assignedPermissions);
if ($roleCrud->update($post['originalRoleName'], $newRole) === true) {
    echo '<p class="dcms-positive-text">Updated the ' . $post['originalRoleName'] . ' Role Successfully...</p>';
} else {
    echo '<p class="dcms-negative-text">The ' . $post['originalRoleName'] . ' Role could not be updated. Please try again...</p>';
}

