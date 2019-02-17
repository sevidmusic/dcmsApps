<?php

use \DarlingCms\classes\staticClasses\core\CoreValues;

if (filter_input(INPUT_POST, 'ajaxRequest') === 'true') {
    require str_replace('/apps/PermissionManager/handlers', '/vendor/autoload.php', __DIR__);
}

$sqlQuery = \DarlingCms\classes\staticClasses\core\CoreMySqlQuery::DbConnection(CoreValues::getPrivilegesDBName());
$actionCrud = new \DarlingCms\classes\crud\MySqlActionCrud($sqlQuery);
$permissionCrud = new \DarlingCms\classes\crud\MySqlPermissionCrud($sqlQuery, $actionCrud);
$post = filter_input_array(INPUT_POST);
$actions = array_merge(
    array_combine(
        (isset($post['assignedActionNames']) ? $post['assignedActionNames'] : array()),
        (isset($post['assignedActionStates']) ? $post['assignedActionStates'] : array())
    ),
    array_combine(
        (isset($post['unAssignedActionNames']) ? $post['unAssignedActionNames'] : array()),
        (isset($post['unAssignedActionStates']) ? $post['unAssignedActionStates'] : array())
    )
);
$assignedActions = array();
foreach ($actions as $actionName => $actionState) {
    if ($actionState === 'true') {
        array_push($assignedActions, $actionCrud->read($actionName));
    }
}
$newPermission = new \DarlingCms\classes\privilege\Permission($post['permissionName'], $assignedActions);
if ($permissionCrud->update($post['originalPermissionName'], $newPermission) === true) {
    echo '<p class="dcms-positive-text">Updated the ' . $post['originalPermissionName'] . ' Permission Successfully...</p>';
} else {
    echo '<p class="dcms-negative-text">The ' . $post['originalPermissionName'] . ' Permission could not be updated. Please try again...</p>';
}

