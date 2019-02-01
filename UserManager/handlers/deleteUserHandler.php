<?php

use \DarlingCms\classes\staticClasses\core\CoreValues;

if (filter_input(INPUT_POST, 'ajaxRequest') === 'true') {
    require str_replace('/apps/RoleManager/handlers', '/vendor/autoload.php', __DIR__);
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
$post = filter_input_array(INPUT_POST);
if ($roleCrud->delete($post['roleName']) == true) {
    echo '<p class="dcms-positive-text">Deleted the ' . $post['roleName'] . ' Role Successfully...</p>';
} else {
    echo '<p class="dcms-negative-text">The ' . $post['roleName'] . ' Role could not be deleted. Please try again...</p>';
}
