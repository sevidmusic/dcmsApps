<?php

use \DarlingCms\classes\staticClasses\core\CoreValues;

require str_replace('/apps/RoleManager/views', '/vendor/autoload.php', __DIR__);

$sqlQuery = \DarlingCms\classes\staticClasses\core\CoreMySqlQuery::DbConnection(CoreValues::getPrivilegesDBName());
$actionCrud = new \DarlingCms\classes\crud\MySqlActionCrud($sqlQuery);
$permissionCrud = new \DarlingCms\classes\crud\MySqlPermissionCrud($sqlQuery, $actionCrud);
$roleCrud = new \DarlingCms\classes\crud\MySqlRoleCrud($sqlQuery, $permissionCrud);
?>
<div class="role-manager-create-role-container">
    <?php
    $role = new \DarlingCms\classes\privilege\Role('', array());
    $roleElementIdPrefix = trim(str_replace(' ', '', $role->getRoleName()));
    $roleNameElementId = $roleElementIdPrefix . 'RoleNameFormElement';
    ?>
    <?php
    $assignedPermissionNames = array();
    foreach ($role->getPermissions() as $action) {
        array_push($assignedPermissionNames, $action->getPermissionName());
    }
    ?>
    <div>
        <div id="<?php echo $roleElementIdPrefix; ?>-role-name"
             class="role-manager-role-name">
            <div class="role-manager-sub-content-container">
                <h3>Enter a name for the new Role</h3>
                <?php
                $roleNameInput = new \DarlingCms\classes\html\form\Text('roleName', $role->getRoleName(), ['id' => $roleNameElementId, 'class' => 'dcms-input-text dcms-focus dcms-hover role-manager-input-text']);
                echo $roleNameInput->getHtml();
                ?>
            </div>
        </div>
        <div class="role-manager-create-role-available-actions">
            <div class="role-manager-sub-content-container">
                <h3>Select the actions that should be assigned to the new Role</h3>
                <?php
                $unAssignedPermissionNames = array();
                $unAssignedPermissionIncrementer = 0;
                foreach ($permissionCrud->readAll() as $action) {
                    if (!in_array($action->getPermissionName(), $assignedPermissionNames, true) === true) {
                        array_push($unAssignedPermissionNames, $action->getPermissionName());
                        $actionCheckbox = new \DarlingCms\classes\html\form\Checkbox($action->getPermissionName() . '-checkbox', $action->getPermissionName(), ['id' => $roleElementIdPrefix . 'UnAssignedPermissionCheckbox' . strval($unAssignedPermissionIncrementer), 'class' => 'dcms-input-checkbox dcms-focus dcms-hover role-manager-input-checkbox']);
                        echo '<div title="Check to assign..." class="role-manager-available-action-checkbox">' . $actionCheckbox->getHtml() . $action->getPermissionName() . '</div>';
                        $unAssignedPermissionIncrementer++;
                    }
                }
                ?>
            </div>
        </div>
        <div class="role-manager-create-role">
            <div class="role-manager-sub-content-container">
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
                $unAssignedPermissionParamStr = '';
                for ($i = 0; $i <= (count($unAssignedPermissionNames) - 1); $i++) {
                    $unAssignedPermissionTargetId = $roleElementIdPrefix . 'UnAssignedPermissionCheckbox' . $i;
                    $unAssignedPermissionParamStr .= '&' . 'unAssignedPermissionNames[]=\'+getElementValue(\'' . $unAssignedPermissionTargetId . '\')+\'' . '&' . 'unAssignedPermissionStates[]=\'+checkboxIsChecked(\'' . $unAssignedPermissionTargetId . '\')+\'';
                }

                $createAjaxReq = \DarlingCms\abstractions\userInterface\AjaxUi::generateAjaxRequest([
                    'issuingApp' => 'RoleManager',
                    'handlerName' => 'createRoleHandler',
                    'outputElementId' => 'RoleManagerView',
                    'requestType' => 'POST',
                    'contentType' => '',
                    'additionalParams' => 'originalRoleName=\'+this.dataset.roleName+\'' . '&' . 'roleName=\'+getElementValue(\'' . $roleNameElementId . '\')+\'' . $assignedPermissionParamStr . $unAssignedPermissionParamStr,
                    'ajaxDirName' => 'handlers',
                    'callFunction' => '',
                    'callContext' => '',
                    'callArgs' => ''
                ]);
                $createButton = new \DarlingCms\classes\html\HtmlTag('button', ['onclick' => 'confirm(\'Are you sure you want to create the ' . $role->getRoleName() . ' role?\') === true ? ' . $createAjaxReq . ' : console.log(\'Canceled request to create the ' . $role->getRoleName() . ' role.\')', 'data-role-name' => $role->getRoleName(), 'class' => 'dcms-button role-manager-create-role-button'], 'Create New Role');
                echo $createButton->getHtml();
                ?>
            </div>
        </div>
    </div>
</div>
