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
if ($permissionCrud->delete($post['permissionName']) == true) {
    echo '<p class="dcms-positive-text">Deleted the ' . $post['permissionName'] . ' Permission Successfully...</p>';
} else {
    echo '<p class="dcms-negative-text">The ' . $post['permissionName'] . ' Permission could not be deleted. Please try again...</p>';
}
