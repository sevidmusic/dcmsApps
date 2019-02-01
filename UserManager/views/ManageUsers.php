<?php

use \DarlingCms\classes\staticClasses\core\CoreValues;

if (filter_input(INPUT_GET, 'ajaxRequest') === 'true') {
    require str_replace('/apps/UserManager/views', '/vendor/autoload.php', __DIR__);
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
$userCrud = new \DarlingCms\classes\crud\MySqlUserCrud($sqlQuery, $roleCrud);
?>
<h1>Manage Users</h1>
<table class="user-manager-table">
    <tr class="user-manager-table-header-row">
        <th class="user-manager-table-header">Name</th>
        <th class="user-manager-table-header">Assigned Roles</th>
        <th class="user-manager-table-header">Available Roles (Not assigned)</th>
        <th class="user-manager-table-header"></th>
        <th class="user-manager-table-header"></th>
    </tr>
    <?php
    foreach ($userCrud->readAll() as $user) {
        $userElementIdPrefix = str_replace(' ', '', $user->getUserName());
        $userNameElementId = $userElementIdPrefix . 'UserNameElement';
        ?>
        <tr class="user-manager-table-row">
            <td id="<?php echo trim(str_replace(' ', '', $user->getUserName())); ?>-user-name"
                class="user-manager-table-user-name">
                <div class="user-manager-table-cell-content-container">
                    <?php
                    $userNameInput = new \DarlingCms\classes\html\form\Text('userName', $user->getUserName(), ['id' => $userNameElementId, 'class' => 'dcms-input-text dcms-focus dcms-hover user-manager-input-text', 'disabled']); // @todo ! *ACTIVE* ! Need to figure out how to let Users that use this User know when the Users's name is changed and update accordingly.*EOL* NOTE:*EOL*- This MUST not create new dependency between the relevant objects*EOL*- This MUST not effect the app's current functional state as it is at present, i.e. MUST not introduce new bugs
                    echo $userNameInput->getHtml();
                    ?>
                </div>
            </td>
            <td>
                <div class="user-manager-table-cell-content-container">
                    <?php
                    $assignedRoleNames = array();
                    $assignedRoleIncrementer = 0;
                    foreach ($user->getRoles() as $role) {
                        array_push($assignedRoleNames, $role->getRoleName());
                        $roleCheckbox = new \DarlingCms\classes\html\form\Checkbox($role->getRoleName() . '-checkbox', $role->getRoleName(), ['id' => $userElementIdPrefix . 'AssignedRoleCheckbox' . strval($assignedRoleIncrementer), 'checked', 'class' => 'dcms-input-checkbox dcms-focus dcms-hover user-manager-input-checkbox']);
                        echo '<div title="Un-check to un-assign..." class="user-manager-assigned-role-checkbox">' . $roleCheckbox->getHtml() . $role->getRoleName() . '</div>';
                        $assignedRoleIncrementer++;
                    }
                    ?>
                </div>
            </td>
            <td>
                <div class="user-manager-table-cell-content-container">
                    <?php
                    $availableRoleNames = array();
                    $availableRoleIncrementer = 0;
                    foreach ($roleCrud->readAll() as $role) {
                        if (in_array($role->getRoleName(), $assignedRoleNames, true) === true) {
                            continue;
                        }
                        array_push($availableRoleNames, $role->getRoleName());
                        $roleCheckbox = new \DarlingCms\classes\html\form\Checkbox($role->getRoleName() . '-checkbox', $role->getRoleName(), ['id' => $userElementIdPrefix . 'AvailableRoleCheckbox' . strval($availableRoleIncrementer), 'class' => 'dcms-input-checkbox dcms-focus dcms-hover user-manager-input-checkbox']);
                        echo '<div title="Check to assign..." class="user-manager-available-role-checkbox">' . $roleCheckbox->getHtml() . $role->getRoleName() . '</div>';
                        $availableRoleIncrementer++;
                    }
                    ?>
                </div>
            </td>
            <td class="user-manager-table-save-changes">
                <div class="user-manager-table-cell-content-container">
                    <?php
                    //
                    // 1. Build param string from assignedRoleNames, we dont need unassigned as any unchecked will be turned off.
                    // 2. append constructed param string to additionalParams string
                    //
                    $assignedRoleParamStr = '';
                    for ($i = 0; $i <= (count($assignedRoleNames) - 1); $i++) {
                        $assignedRoleTargetId = $userElementIdPrefix . 'AssignedRoleCheckbox' . $i;
                        $assignedRoleParamStr .= '&' . 'assignedRoleNames[]=\'+getElementValue(\'' . $assignedRoleTargetId . '\')+\'' . '&' . 'assignedRoleStates[]=\'+checkboxIsChecked(\'' . $assignedRoleTargetId . '\')+\'';
                    }
                    $availableRoleParamStr = '';
                    for ($i = 0; $i <= (count($availableRoleNames) - 1); $i++) {
                        $availableRoleTargetId = $userElementIdPrefix . 'AvailableRoleCheckbox' . $i;
                        $availableRoleParamStr .= '&' . 'availableRoleNames[]=\'+getElementValue(\'' . $availableRoleTargetId . '\')+\'' . '&' . 'availableRoleStates[]=\'+checkboxIsChecked(\'' . $availableRoleTargetId . '\')+\'';
                    }

                    $updateAjaxReq = \DarlingCms\abstractions\userInterface\AjaxUi::generateAjaxRequest([
                        'issuingApp' => 'UserManager',
                        'handlerName' => 'updateUserHandler',
                        'outputElementId' => 'UserManagerView',
                        'requestType' => 'POST',
                        'contentType' => '',
                        'additionalParams' => 'originalUserName=\'+this.dataset.userName+\'' . '&' . 'userName=\'+getElementValue(\'' . $userNameElementId . '\')+\'' . $assignedRoleParamStr . $availableRoleParamStr,
                        'ajaxDirName' => 'handlers',
                        'callFunction' => '',
                        'callContext' => '',
                        'callArgs' => ''
                    ]);
                    $updateButton = new \DarlingCms\classes\html\HtmlTag('button', ['onclick' => 'confirm(\'Are you sure you want to update the ' . $role->getRoleName() . ' role?\') === true ? ' . $updateAjaxReq . ' : console.log(\'Canceled request to update the ' . $role->getRoleName() . ' role.\')', 'data-user-name' => $user->getUserName(), 'class' => 'dcms-button role-manager-update-role-button'], 'Update User');
                    echo $updateButton->getHtml();
                    ?>
                </div>
            </td>
            <td class="user-manager-table-delete-user">
                <div class="user-manager-table-cell-content-container">
                    <?php
                    $deleteAjaxReq = \DarlingCms\abstractions\userInterface\AjaxUi::generateAjaxRequest([
                        'issuingApp' => 'UserManager',
                        'handlerName' => 'deleteUserHandler',
                        'outputElementId' => 'UserManagerView',
                        'requestType' => 'POST',
                        'contentType' => '',
                        'additionalParams' => 'userName=\'+getElementValue(\'' . $userNameElementId . '\')+\'',
                        'ajaxDirName' => 'handlers',
                        'callFunction' => '',
                        'callContext' => '',
                        'callArgs' => ''
                    ]);
                    $deleteButton = new \DarlingCms\classes\html\HtmlTag('button', ['onclick' => 'confirm(\'Are you sure you want to delete the ' . $user->getUserName() . ' user?\') === true ? ' . $deleteAjaxReq . ' : console.log(\'Canceled request to delete the ' . $user->getUserName() . ' user.\')', 'data-user-name' => $user->getUserName(), 'class' => 'dcms-button user-manager-delete-user-button'], 'Delete User');
                    echo $deleteButton->getHtml();
                    ?>
                </div>
            </td>
        </tr>
        <?php
    }
    ?>
</table>
