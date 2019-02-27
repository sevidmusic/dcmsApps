<?php

if (filter_input(INPUT_POST, 'ajaxRequest') === 'true') {
    require str_replace('/apps/UserManager/handlers', '/vendor/autoload.php', __DIR__);
}

$crudFactory = new \DarlingCms\classes\factory\CoreMySqlCrudFactory();
$actionCrud = $crudFactory->getActionCrud();
$permissionCrud = $crudFactory->getPermissionCrud();
$roleCrud = $crudFactory->getRoleCrud();
$userCrud = $crudFactory->getUserCrud();
$passwordCrud = $crudFactory->getPasswordCrud();
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
var_dump($post);

if (!empty($post['userName']) === true && !empty($post['userPassword']) === true) {
    $newUser = new \DarlingCms\classes\user\User($post['userName'], [], $assignedRoles);
    $newUserPassword = new \DarlingCms\classes\user\UserPassword($newUser, $post['userPassword']);
    if ($userCrud->create($newUser) === true && $passwordCrud->create($newUserPassword) === true) {
        echo '<p class="dcms-positive-text">Created the new ' . $post['userName'] . ' User Successfully...</p>';
    } else {
        echo '<p class="dcms-negative-text">The ' . $post['userName'] . ' User could not be created. Please try again...</p>';
    }
} else {
    echo '<p class="dcms-negative-text">The user could not be created because a username and/or password were not provided. Please try again...</p>';
}

