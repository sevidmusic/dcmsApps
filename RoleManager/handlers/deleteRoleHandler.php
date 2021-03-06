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
if ($roleCrud->delete($post['roleName']) == true) {
    echo '<p class="dcms-positive-text">Deleted the ' . $post['roleName'] . ' Role Successfully...</p>';
} else {
    echo '<p class="dcms-negative-text">The ' . $post['roleName'] . ' Role could not be deleted. Please try again...</p>';
}
