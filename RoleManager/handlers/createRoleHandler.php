<?php

use \DarlingCms\classes\staticClasses\core\CoreValues;

if (filter_input(INPUT_POST, 'ajaxRequest') === 'true') {
    require str_replace('/apps/RoleManager/handlers', '/vendor/autoload.php', __DIR__);
}

$sqlQuery = CoreValues::getMySqlQueryInstance
(
    CoreValues::CORE_DB_HOST,
    CoreValues::CORE_DB_NAME,
    'root',
    'root'
);
$actionCrud = new \DarlingCms\classes\crud\MySqlActionCrud($sqlQuery);
$permissionCrud = new \DarlingCms\classes\crud\MySqlPermissionCrud($sqlQuery, $actionCrud);
$roleCrud = new \DarlingCms\classes\crud\MySqlRoleCrud($sqlQuery, $permissionCrud);
$post = filter_input_array(INPUT_POST);
$permissions = array_merge(
    array_combine(
        (isset($post['assignedPermissionNames']) ? $post['assignedPermissionNames'] : array()),
        (isset($post['assignedPermissionStates']) ? $post['assignedPermissionStates'] : array())
    ),
    array_combine(
        (isset($post['unAssignedPermissionNames']) ? $post['unAssignedPermissionNames'] : array()),
        (isset($post['unAssignedPermissionStates']) ? $post['unAssignedPermissionStates'] : array())
    )
);
$assignedPermissions = array();
foreach ($permissions as $permissionName => $permissionState) {
    if ($permissionState === 'true') {
        array_push($assignedPermissions, $permissionCrud->read($permissionName));
    }
}
$newRole = new \DarlingCms\classes\privilege\Role($post['roleName'], $assignedPermissions);
if ($roleCrud->create($newRole) === true) {
    echo '<p class="dcms-positive-text">Created the new ' . $post['roleName'] . ' Role Successfully...</p>';
} else {
    echo '<p class="dcms-negative-text">The ' . $post['roleName'] . ' Role could not be created. Please try again...</p>';
}
