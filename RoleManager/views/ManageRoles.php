<?php

use \DarlingCms\classes\staticClasses\core\CoreValues;

if (filter_input(INPUT_GET, 'ajaxRequest') === 'true') {
    require str_replace('/apps/RoleManager/views', '/vendor/autoload.php', __DIR__);
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
?>
<h1>Manage Roles</h1>
<table class="role-manager-table">
    <tr class="role-manager-table-header-row">
        <th class="role-manager-table-header">Name</th>
        <th class="role-manager-table-header">Assigned Permissions</th>
        <th class="role-manager-table-header">Available Permissions (Not assigned)</th>
        <th class="role-manager-table-header"></th>
        <th class="role-manager-table-header"></th>
    </tr>
    <?php
    foreach ($roleCrud->readAll() as $role) {
        $roleElementIdPrefix = str_replace(' ', '', $role->getRoleName());
        $roleNameElementId = $roleElementIdPrefix . 'RoleNameElement';
        ?>
        <tr class="role-manager-table-row">
            <td id="<?php echo trim(str_replace(' ', '', $role->getRoleName())); ?>-role-name"
                class="role-manager-table-role-name">
                <div class="role-manager-table-cell-content-container">
                    <?php
                    $roleNameInput = new \DarlingCms\classes\html\form\Text('roleName', $role->getRoleName(), ['id' => $roleNameElementId, 'class' => 'dcms-input-text dcms-focus dcms-hover role-manager-input-text', 'disabled']); // @todo ! *ACTIVE* ! Need to figure out how to let Users that use this Role know when the Roles's name is changed and update accordingly.*EOL* NOTE:*EOL*- This MUST not create new dependency between the relevant objects*EOL*- This MUST not effect the app's current functional state as it is at present, i.e. MUST not introduce new bugs
                    echo $roleNameInput->getHtml();
                    ?>
                </div>
            </td>
            <td>
                <div class="role-manager-table-cell-content-container">
                    <?php
                    $assignedPermissionNames = array();
                    $assignedPermissionIncrementer = 0;
                    foreach ($role->getPermissions() as $permission) {
                        array_push($assignedPermissionNames, $permission->getPermissionName());
                        $permissionCheckbox = new \DarlingCms\classes\html\form\Checkbox($permission->getPermissionName() . '-checkbox', $permission->getPermissionName(), ['id' => $roleElementIdPrefix . 'AssignedPermissionCheckbox' . strval($assignedPermissionIncrementer), 'checked', 'class' => 'dcms-input-checkbox dcms-focus dcms-hover role-manager-input-checkbox']);
                        echo '<div title="Un-check to un-assign..." class="role-manager-assigned-permission-checkbox">' . $permissionCheckbox->getHtml() . $permission->getPermissionName() . '</div>';
                        $assignedPermissionIncrementer++;
                    }
                    ?>
                </div>
            </td>
            <td>
                <div class="role-manager-table-cell-content-container">
                    <?php
                    $availablePermissionNames = array();
                    $availablePermissionIncrementer = 0;
                    foreach ($permissionCrud->readAll() as $permission) {
                        if (in_array($permission->getPermissionName(), $assignedPermissionNames, true) === true) {
                            continue;
                        }
                        array_push($availablePermissionNames, $permission->getPermissionName());
                        $permissionCheckbox = new \DarlingCms\classes\html\form\Checkbox($permission->getPermissionName() . '-checkbox', $permission->getPermissionName(), ['id' => $roleElementIdPrefix . 'AvailablePermissionCheckbox' . strval($availablePermissionIncrementer), 'class' => 'dcms-input-checkbox dcms-focus dcms-hover role-manager-input-checkbox']);
                        echo '<div title="Check to assign..." class="role-manager-available-permission-checkbox">' . $permissionCheckbox->getHtml() . $permission->getPermissionName() . '</div>';
                        $availablePermissionIncrementer++;
                    }
                    ?>
                </div>
            </td>
            <td class="role-manager-table-save-changes">
                <div class="role-manager-table-cell-content-container">
                    <?php
                    //
                    // 1. Build param string from assignedPermissionNames, we dont need unassigned as any unchecked will be turned off.
                    // 2. append constructed param string to additionalParams string
                    //
                    $assignedPermissionParamStr = '';
                    for ($i = 0; $i <= (count($assignedPermissionNames) - 1); $i++) {
                        $assignedPermissionTargetId = $roleElementIdPrefix . 'AssignedPermissionCheckbox' . $i;
                        $assignedPermissionParamStr .= '&' . 'assignedPermissionNames[]=\'+getElementValue(\'' . $assignedPermissionTargetId . '\')+\'' . '&' . 'assignedPermissionStates[]=\'+checkboxIsChecked(\'' . $assignedPermissionTargetId . '\')+\'';
                    }
                    $availablePermissionParamStr = '';
                    for ($i = 0; $i <= (count($availablePermissionNames) - 1); $i++) {
                        $availablePermissionTargetId = $roleElementIdPrefix . 'AvailablePermissionCheckbox' . $i;
                        $availablePermissionParamStr .= '&' . 'availablePermissionNames[]=\'+getElementValue(\'' . $availablePermissionTargetId . '\')+\'' . '&' . 'availablePermissionStates[]=\'+checkboxIsChecked(\'' . $availablePermissionTargetId . '\')+\'';
                    }

                    $updateAjaxReq = \DarlingCms\abstractions\userInterface\AjaxUi::generateAjaxRequest([
                        'issuingApp' => 'RoleManager',
                        'handlerName' => 'updateRoleHandler',
                        'outputElementId' => 'RoleManagerView',
                        'requestType' => 'POST',
                        'contentType' => '',
                        'additionalParams' => 'originalRoleName=\'+this.dataset.roleName+\'' . '&' . 'roleName=\'+getElementValue(\'' . $roleNameElementId . '\')+\'' . $assignedPermissionParamStr . $availablePermissionParamStr,
                        'ajaxDirName' => 'handlers',
                        'callFunction' => '',
                        'callContext' => '',
                        'callArgs' => ''
                    ]);
                    $updateButton = new \DarlingCms\classes\html\HtmlTag('button', ['onclick' => 'confirm(\'Are you sure you want to update the ' . $permission->getPermissionName() . ' permission?\') === true ? ' . $updateAjaxReq . ' : console.log(\'Canceled request to update the ' . $permission->getPermissionName() . ' permission.\')', 'data-role-name' => $role->getRoleName(), 'class' => 'dcms-button permission-manager-update-permission-button'], 'Update Role');
                    echo $updateButton->getHtml();
                    ?>
                </div>
            </td>
            <td class="role-manager-table-delete-role">
                <div class="role-manager-table-cell-content-container">
                    <?php
                    $deleteAjaxReq = \DarlingCms\abstractions\userInterface\AjaxUi::generateAjaxRequest([
                        'issuingApp' => 'RoleManager',
                        'handlerName' => 'deleteRoleHandler',
                        'outputElementId' => 'RoleManagerView',
                        'requestType' => 'POST',
                        'contentType' => '',
                        'additionalParams' => 'roleName=\'+getElementValue(\'' . $roleNameElementId . '\')+\'',
                        'ajaxDirName' => 'handlers',
                        'callFunction' => '',
                        'callContext' => '',
                        'callArgs' => ''
                    ]);
                    $deleteButton = new \DarlingCms\classes\html\HtmlTag('button', ['onclick' => 'confirm(\'Are you sure you want to delete the ' . $role->getRoleName() . ' role?\') === true ? ' . $deleteAjaxReq . ' : console.log(\'Canceled request to delete the ' . $role->getRoleName() . ' role.\')', 'data-role-name' => $role->getRoleName(), 'class' => 'dcms-button role-manager-delete-role-button'], 'Delete Role');
                    echo $deleteButton->getHtml();
                    ?>
                </div>
            </td>
        </tr>
        <?php
    }
    ?>
</table>

