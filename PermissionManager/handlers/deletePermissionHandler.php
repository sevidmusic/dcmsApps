<?php

use \DarlingCms\classes\staticClasses\core\CoreValues;

if (filter_input(INPUT_POST, 'ajaxRequest') === 'true') {
    require str_replace('/apps/PermissionManager/handlers', '/vendor/autoload.php', __DIR__);
}

$crudFactory = new \DarlingCms\classes\factory\CoreMySqlCrudFactory();
$actionCrud = $crudFactory->getActionCrud();
$permissionCrud = $crudFactory->getPermissionCrud();
$post = filter_input_array(INPUT_POST);
if ($permissionCrud->delete($post['permissionName']) == true) {
    echo '<p class="dcms-positive-text">Deleted the ' . $post['permissionName'] . ' Permission Successfully...</p>';
} else {
    echo '<p class="dcms-negative-text">The ' . $post['permissionName'] . ' Permission could not be deleted. Please try again...</p>';
}
