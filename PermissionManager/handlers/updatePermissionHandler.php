<?php

use \DarlingCms\classes\staticClasses\core\CoreValues;

if (filter_input(INPUT_POST, 'ajaxRequest') === 'true') {
    require str_replace('/apps/PermissionManager/handlers', '/vendor/autoload.php', __DIR__);
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
$post = filter_input_array(INPUT_POST);
$actions = array_merge(array_combine($post['assignedActionNames'], $post['assignedActionStates']), array_combine($post['unAssignedActionNames'], $post['unAssignedActionStates']));
$assignedActions = array();
foreach ($actions as $actionName => $actionState) {
    if ($actionState === 'true') {
        array_push($assignedActions, $actionCrud->read($actionName));
    }
}
$newPermission = new \DarlingCms\classes\privilege\Permission($post['permissionName'], $assignedActions);
var_dump($permissionCrud->update($post['originalPermissionName'], $newPermission));
?>
<p>This will update permissions...</p>
