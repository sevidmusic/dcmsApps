<?php

use DarlingCms\classes\staticClasses\core\CoreValues;

$crudFactory = new \DarlingCms\classes\factory\CoreMySqlCrudFactory();
$actionCrud = $crudFactory->getActionCrud();
$permissionCrud = $crudFactory->getPermissionCrud();
$roleCrud = $crudFactory->getRoleCrud();
$userCrud = $crudFactory->getUserCrud(); // @todo ! Add to CoreValues
$passwordCrud = $crudFactory->getPasswordCrud(); // @todo ! Add to CoreValues
$userLogin = new \DarlingCms\classes\accessControl\UserLogin(); // @todo ! Add to CoreValues
$submittedUserName = (empty(filter_input(INPUT_POST, 'userName')) === false ? filter_input(INPUT_POST, 'userName') : (empty($userLogin->read(\DarlingCms\classes\accessControl\UserLogin::CURRENT_USER_POST_VAR_NAME)) === false ? $userLogin->read(\DarlingCms\classes\accessControl\UserLogin::CURRENT_USER_POST_VAR_NAME) : ''));
?>
<div id="UserLoginContainer" class="user-login-container">
    <?php
    $loader = '<div class="dcms-loader user-login-loader-spinner"></div>';
    echo '<p class="user-login-text user-login-status-msg">' . ($userLogin->isLoggedIn($submittedUserName) === true ? 'You are logged in as "' . $userCrud->read($submittedUserName)->getUserName() . '"' : 'You are not logged in:') . '</p>';


    // Check if user login form was submitted
    if (filter_input(INPUT_POST, $userLogin::LOGIN_STATE_VAR_NAME) === $userLogin::LOGIN_STATE_VAR_VALUE) {
        if ($userLogin->login($userCrud->read($submittedUserName), $passwordCrud->read($userCrud->read($submittedUserName))) === true) {
            ?>
            <p class="dcms-positive-text dcms-float-left user-login-text user-login-status-msg">You are now logged
                in. You will be re-directed shortly...</p>
            <script type="text/javascript">
                /* Assign js vars used by postLoginRedirect.js */
                window.redirectUser = true;
                window.redirectUrl = '<?php echo CoreValues::getSiteRootUrl(); ?>';
            </script>
            <?php
            echo $loader;
        } else {
            echo '<p class="dcms-negative-text dcms-float-left user-login-text user-login-status-msg">Login failed. Please try again.</p>';
        }
    }


    // Check if user logout form was submitted
    if (filter_input(INPUT_POST, $userLogin::LOGOUT_STATE_VAR_NAME) === $userLogin::LOGOUT_STATE_VAR_VALUE) {
        if ($userLogin->logout($submittedUserName) === true) {
            ?>
            <p class="dcms-positive-text dcms-float-left user-login-text user-login-status-msg">You are now logged
                out. You will be re-directed shortly...</p>
            <script type="text/javascript">
                /* Assign js vars used by postLoginRedirect.js */
                window.redirectUser = true;
                window.redirectUrl = '<?php echo CoreValues::getSiteRootUrl(); ?>';
            </script>
            <?php
            echo $loader;
        } else {
            echo '<p class="dcms-negative-text dcms-float-left user-login-text user-login-status-msg">Logout failed. Please try again.</p>';
        }
    }
    // Display appropriate form based on whether or not user is logged in.
    switch ($userLogin->isLoggedIn($submittedUserName)) {
        case false:
            $form = new \DarlingCms\classes\html\form\Form(
                'POST',
                ['class' => 'dcms-float-right user-login-form']
                ,
                new \DarlingCms\classes\html\form\Text('userName', '', ['placeholder' => 'Username', 'class' => 'dcms-input-text dcms-focus dcms-hover user-login-user-name-text-input']),
                new \DarlingCms\classes\html\form\Password('password', '', ['placeholder' => 'Password', 'class' => 'dcms-input-text dcms-focus dcms-hover user-login-user-password-input']),
                new \DarlingCms\classes\html\form\Submit($userLogin::LOGIN_STATE_VAR_NAME, $userLogin::LOGIN_STATE_VAR_VALUE, ['class' => 'dcms-button dcms-focus dcms-hover user-login-submit-button'])
            );
            echo $form->getHtml();
            break;
        case true:
            $form = new \DarlingCms\classes\html\form\Form(
                'POST',
                ['class' => 'dcms-float-right user-logout-form']
                ,
                new \DarlingCms\classes\html\form\Submit($userLogin::LOGOUT_STATE_VAR_NAME, $userLogin::LOGOUT_STATE_VAR_VALUE, ['class' => 'dcms-button dcms-focus dcms-hover user-logout-submit-button'])
            );
            echo $form->getHtml();
            break;
    }
    ?>
    <div class="dcms-clearfix"></div>
</div>
