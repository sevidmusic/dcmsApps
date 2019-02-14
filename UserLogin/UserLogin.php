<?php

use DarlingCms\classes\staticClasses\core\CoreValues;

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
$passwordCrud = new \DarlingCms\classes\crud\MySqlUserPasswordCrud($sqlQuery);
$userLogin = new \DarlingCms\classes\accessControl\UserLogin(/*$userCrud, $passwordCrud*/);
$submittedUserName = (empty(filter_input(INPUT_POST, 'userName')) === false ? filter_input(INPUT_POST, 'userName') : (empty($userLogin->read(\DarlingCms\classes\accessControl\UserLogin::CURRENT_USER_POST_VAR_NAME)) === false ? $userLogin->read(\DarlingCms\classes\accessControl\UserLogin::CURRENT_USER_POST_VAR_NAME) : ''));
?>
<div id="UserLoginContainer" class="">
    <?php
    echo '<p>' . ($userLogin->isLoggedIn($submittedUserName) === true ? 'User ' . $userCrud->read($submittedUserName)->getUserName() . ' is logged in:' : 'User ' . $userCrud->read($submittedUserName)->getUserName() . ' is not logged in:') . '</p>';


    // Check if user login form was submitted
    if (filter_input(INPUT_POST, 'loginUser') === 'Login') {
        if ($userLogin->login($userCrud->read($submittedUserName), $passwordCrud->read($userCrud->read($submittedUserName))) === true) {
            echo '<p class="dcms-positive-text">You are now logged in.</p>';
        } else {
            echo '<p class="dcms-negative-text">Login failed. Please try again.</p>';
        }
    }


    // Check if user logout form was submitted
    if (filter_input(INPUT_POST, 'logoutUser') === 'Logout') {
        if ($userLogin->logout($submittedUserName) === true) {
            echo '<p class="dcms-positive-text">You are now logged out.</p>';
        } else {
            echo '<p class="dcms-negative-text">Logout failed. Please try again.</p>';
        }
    }
    // Display appropriate form based on whether or not user is logged in.
    switch ($userLogin->isLoggedIn($submittedUserName)) {
        case false:
            $form = new \DarlingCms\classes\html\form\Form(
                'POST',
                []
                ,
                new \DarlingCms\classes\html\form\Text('userName', '', ['placeholder' => 'Username', 'class' => 'dcms-input-text dcms-focus dcms-hover']),
                new \DarlingCms\classes\html\form\Password('password', '', ['placeholder' => 'Password', 'class' => 'dcms-input-text dcms-focus dcms-hover']),
                new \DarlingCms\classes\html\form\Submit('loginUser', 'Login', ['class' => 'dcms-button dcms-focus dcms-hover'])
            );
            echo $form->getHtml();
            break;
        case true:
            $form = new \DarlingCms\classes\html\form\Form(
                'POST',
                []
                ,
                new \DarlingCms\classes\html\form\Submit('logoutUser', 'Logout', ['class' => 'dcms-button dcms-focus dcms-hover'])
            );
            echo $form->getHtml();
            break;
    }
    ?>
</div>
