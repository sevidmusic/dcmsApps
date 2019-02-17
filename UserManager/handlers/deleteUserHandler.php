<?php

use \DarlingCms\classes\staticClasses\core\CoreValues;

if (filter_input(INPUT_POST, 'ajaxRequest') === 'true') {
    require str_replace('/apps/UserManager/handlers', '/vendor/autoload.php', __DIR__);
}

$sqlQuery = \DarlingCms\classes\staticClasses\core\CoreMySqlQuery::DbConnection(CoreValues::USERS_DB_NAME);
$actionCrud = new \DarlingCms\classes\crud\MySqlActionCrud($sqlQuery);
$permissionCrud = new \DarlingCms\classes\crud\MySqlPermissionCrud($sqlQuery, $actionCrud);
$roleCrud = new \DarlingCms\classes\crud\MySqlRoleCrud($sqlQuery, $permissionCrud);
$userCrud = new \DarlingCms\classes\crud\MySqlUserCrud($sqlQuery, $roleCrud);
$post = filter_input_array(INPUT_POST);
if ($userCrud->delete($post['userName']) == true) {
    echo '<p class="dcms-positive-text">Deleted the ' . $post['userName'] . ' User Successfully...</p>';
} else {
    echo '<p class="dcms-negative-text">The ' . $post['userName'] . ' User could not be deleted. Please try again...</p>';
}
