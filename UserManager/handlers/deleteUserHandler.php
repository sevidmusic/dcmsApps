<?php

if (filter_input(INPUT_POST, 'ajaxRequest') === 'true') {
    require str_replace('/apps/UserManager/handlers', '/vendor/autoload.php', __DIR__);
}

$crudFactory = new \DarlingCms\classes\factory\CoreMySqlCrudFactory();
$actionCrud = $crudFactory->getActionCrud();
$permissionCrud = $crudFactory->getPermissionCrud();
$roleCrud = $crudFactory->getRoleCrud();
$userCrud = $crudFactory->getUserCrud();
$post = filter_input_array(INPUT_POST);
if ($userCrud->delete($post['userName']) == true) {
    echo '<p class="dcms-positive-text">Deleted the ' . $post['userName'] . ' User Successfully...</p>';
} else {
    echo '<p class="dcms-negative-text">The ' . $post['userName'] . ' User could not be deleted. Please try again...</p>';
}
