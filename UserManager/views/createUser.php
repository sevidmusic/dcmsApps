<?php

use \DarlingCms\classes\staticClasses\core\CoreValues;

require str_replace('/apps/UserManager/views', '/vendor/autoload.php', __DIR__);

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
?>
<div class="user-manager-create-user-container">
    <?php
    $user = new \DarlingCms\classes\user\User('', array());
    $userElementIdPrefix = trim(str_replace(' ', '', $user->getUserName()));
    $userNameElementId = $userElementIdPrefix . 'UserNameFormElement';
    ?>
    <?php
    $assignedRoleNames = array();
    foreach ($user->getRoles() as $role) {
        array_push($assignedRoleNames, $role->getRoleName());
    }
    ?>
    <div>
        <div id="<?php echo $userElementIdPrefix; ?>-user-name"
             class="user-manager-user-name">
            <div class="user-manager-sub-content-container">
                <h3>Enter a name for the new User</h3>
                <?php
                $userNameInput = new \DarlingCms\classes\html\form\Text('userName', $user->getUserName(), ['id' => $userNameElementId, 'class' => 'dcms-input-text dcms-focus dcms-hover user-manager-input-text']);
                echo $userNameInput->getHtml();
                ?>
            </div>
        </div>
        <div class="user-manager-create-user-available-roles">
            <div class="user-manager-sub-content-container">
                <h3>Select the roles that should be assigned to the new User</h3>
                <?php
                $unAssignedRoleNames = array();
                $unAssignedRoleIncrementer = 0;
                foreach ($roleCrud->readAll() as $roles) {
                    if (!in_array($roles->getRoleName(), $assignedRoleNames, true) === true) {
                        array_push($unAssignedRoleNames, $roles->getRoleName());
                        $rolesCheckbox = new \DarlingCms\classes\html\form\Checkbox($roles->getRoleName() . '-checkbox', $roles->getRoleName(), ['id' => $userElementIdPrefix . 'UnAssignedRoleCheckbox' . strval($unAssignedRoleIncrementer), 'class' => 'dcms-input-checkbox dcms-focus dcms-hover user-manager-input-checkbox']);
                        echo '<div title="Check to assign..." class="user-manager-available-roles-checkbox">' . $rolesCheckbox->getHtml() . $roles->getRoleName() . '</div>';
                        $unAssignedRoleIncrementer++;
                    }
                }
                ?>
            </div>
        </div>
        <div class="user-manager-create-user">
            <div class="user-manager-sub-content-container">
                <?php
                $assignedRoleParamStr = '';
                for ($i = 0; $i <= (count($assignedRoleNames) - 1); $i++) {
                    $assignedRoleTargetId = $userElementIdPrefix . 'AssignedRoleCheckbox' . $i;
                    $assignedRoleParamStr .= '&' . 'assignedRoleNames[]=\'+getElementValue(\'' . $assignedRoleTargetId . '\')+\'' . '&' . 'assignedRoleStates[]=\'+checkboxIsChecked(\'' . $assignedRoleTargetId . '\')+\'';
                }
                $unAssignedRoleParamStr = '';
                for ($i = 0; $i <= (count($unAssignedRoleNames) - 1); $i++) {
                    $unAssignedRoleTargetId = $userElementIdPrefix . 'UnAssignedRoleCheckbox' . $i;
                    $unAssignedRoleParamStr .= '&' . 'unAssignedRoleNames[]=\'+getElementValue(\'' . $unAssignedRoleTargetId . '\')+\'' . '&' . 'unAssignedRoleStates[]=\'+checkboxIsChecked(\'' . $unAssignedRoleTargetId . '\')+\'';
                }

                $createAjaxReq = \DarlingCms\abstractions\userInterface\AjaxUi::generateAjaxRequest([
                    'issuingApp' => 'UserManager',
                    'handlerName' => 'createUserHandler',
                    'outputElementId' => 'UserManagerView',
                    'requestType' => 'POST',
                    'contentType' => '',
                    'additionalParams' => 'originalUserName=\'+this.dataset.userName+\'' . '&' . 'userName=\'+getElementValue(\'' . $userNameElementId . '\')+\'' . $assignedRoleParamStr . $unAssignedRoleParamStr,
                    'ajaxDirName' => 'handlers',
                    'callFunction' => '',
                    'callContext' => '',
                    'callArgs' => ''
                ]);
                $createButton = new \DarlingCms\classes\html\HtmlTag('button', ['onclick' => 'confirm(\'Are you sure you want to create the ' . $user->getUserName() . ' user?\') === true ? ' . $createAjaxReq . ' : console.log(\'Canceled request to create the ' . $user->getUserName() . ' user.\')', 'data-user-name' => $user->getUserName(), 'class' => 'dcms-button user-manager-create-user-button'], 'Create New User');
                echo $createButton->getHtml();
                ?>
            </div>
        </div>
    </div>
</div>