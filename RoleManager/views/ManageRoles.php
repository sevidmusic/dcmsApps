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
        <th class="role-manager-table-header">Available Permissions</th>
        <th class="role-manager-table-header"></th>
        <th class="role-manager-table-header"></th>
    </tr>
    <?php
    foreach ($roleCrud->readAll() as $role) {
        $roleElementIdPrefix = str_replace(' ', '', $role->getRoleName());
        $roleNameFormElementId = $roleElementIdPrefix . 'ActionNameFormElement';
        ?>
        <tr class="role-manager-table-row">
            <td id="<?php echo trim(str_replace(' ', '', $role->getRoleName())); ?>-role-name"
                class="role-manager-table-role-name">
                <div class="role-manager-table-cell-content-container">
                    <?php
                    $roleNameInput = new \DarlingCms\classes\html\form\Text('roleName', $role->getRoleName(), ['id' => $roleNameFormElementId, 'class' => 'dcms-input-text dcms-focus dcms-hover role-manager-input-text']);
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
                        $permissionCheckbox = new \DarlingCms\classes\html\form\Checkbox($permission->getPermissionName() . '-checkbox', $permission->getPermissionName(), ['id' => $roleElementIdPrefix . 'AssignedActionCheckbox' . strval($assignedPermissionIncrementer), 'checked', 'class' => 'dcms-input-checkbox dcms-focus dcms-hover role-manager-input-checkbox']);
                        echo '<div title="Un-check to un-assign..." class="role-manager-assigned-permission-checkbox">' . $permissionCheckbox->getHtml() . $permission->getPermissionName() . '</div>';
                        $assignedPermissionIncrementer++;
                    }
                    ?>
                    <?php
                    /*
                    foreach ($role->getPermissions() as $permission) {
                        ?>
                        <div class="role-manager-assigned-permission-checkbox">
                            <label>
                                <input type="checkbox" value="PermissionName" checked>
                                <?php echo $permission->getPermissionName(); ?>
                            </label>
                        </div>
                        <?php
                    }
                    */
                    ?>
                </div>
            </td>
            <td>
                <div class="role-manager-table-cell-content-container">
                    <?php
                    /*
                    foreach ($permissionCrud->readAll() as $permission) {
                        ?>
                        <div class="role-manager-available-permission-checkbox">
                            <label>
                                <input type="checkbox" value="PermissionName">
                                <?php echo $permission->getPermissionName(); ?>
                            </label>
                        </div>
                        <?php
                    }
                    */
                    ?>
                </div>
            </td>
            <td class="role-manager-table-save-changes">
                <div class="role-manager-table-cell-content-container">
                    <?php
                    $updateAjaxReq = \DarlingCms\abstractions\userInterface\AjaxUi::generateAjaxRequest([
                        'issuingApp' => 'RoleManager',
                        'handlerName' => 'updateRoleHandler',
                        'outputElementId' => 'RoleManagerView',
                        'requestType' => 'POST',
                        'contentType' => '',
                        // 'additionalParams' => 'roleName=\'+this.dataset.roleName+\'' . '&' . 'roleDescription=\'+this.dataset.roleDescription+\'',// @todo this should actually reference sybilings to get text and textarea values @see https://www.w3schools.com/jsref/tryit.asp?filename=tryjsref_node_previoussibling
                        'additionalParams' => 'roleName=\'+getElementValue(\'' . $roleNameFormElementId . '\')+\'',
                        'ajaxDirName' => 'handlers',
                        'callFunction' => '',
                        'callContext' => '',
                        'callArgs' => ''
                    ]);
                    $updateButton = new \DarlingCms\classes\html\HtmlTag('button', ['onclick' => 'confirm(\'Are you sure you want to update the ' . $role->getRoleName() . ' role?\') === true ? ' . $updateAjaxReq . ' : console.log(\'Canceled request to update the ' . $role->getRoleName() . ' role.\')', 'data-role-name' => $role->getRoleName(), 'class' => 'dcms-button role-manager-update-role-button'], 'update Role');
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
                        'additionalParams' => 'roleName=\'+getElementValue(\'' . $roleNameFormElementId . '\')+\'',
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

