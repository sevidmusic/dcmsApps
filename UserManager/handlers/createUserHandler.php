<?php

use DarlingCms\classes\staticClasses\core\CoreValues;

if (filter_input(INPUT_POST, 'ajaxRequest') === 'true') {
    require str_replace('/apps/UserManager/handlers', '/vendor/autoload.php', __DIR__);
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
$userCrud = new \DarlingCms\classes\crud\MySqlUserCrud($sqlQuery, $roleCrud);
$post = filter_input_array(INPUT_POST);
$roles = array_merge(
    array_combine(
        (isset($post['assignedRoleNames']) ? $post['assignedRoleNames'] : array()),
        (isset($post['assignedRoleStates']) ? $post['assignedRoleStates'] : array())
    ),
    array_combine(
        (isset($post['unAssignedRoleNames']) ? $post['unAssignedRoleNames'] : array()),
        (isset($post['unAssignedRoleStates']) ? $post['unAssignedRoleStates'] : array())
    )
);
$assignedRoles = array();
foreach ($roles as $roleName => $roleState) {
    if ($roleState === 'true') {
        array_push($assignedRoles, $roleCrud->read($roleName));
    }
}
$newUser = new \DarlingCms\classes\user\User($post['userName'], $assignedRoles);
if ($userCrud->create($newUser) === true) {
    echo '<p class="dcms-positive-text">Created the new ' . $post['userName'] . ' User Successfully...</p>';
} else {
    echo '<p class="dcms-negative-text">The ' . $post['userName'] . ' User could not be created. Please try again...</p>';
}
