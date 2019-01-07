<?php

use \DarlingCms\classes\staticClasses\core\CoreValues;

if (filter_input(INPUT_GET, 'ajaxRequest') === 'true') {
    require str_replace('/apps/PermissionManager/views', '/vendor/autoload.php', __DIR__);
}
$sqlQuery = CoreValues::getISqlQueryInstance
(
    CoreValues::CORE_DB_HOST,
    CoreValues::CORE_DB_NAME,
    'root',
    'root'
);
$permissionCrud = new \DarlingCms\classes\crud\MySqlPermissionCrud($sqlQuery, new \DarlingCms\classes\crud\MySqlActionCrud($sqlQuery));
?>
<h1>Manage Permissions</h1>
<table class="permission-manager-table">
    <tr class="permission-manager-table-header-row">
        <th class="permission-manager-table-header">Name</th>
        <th class="permission-manager-table-header">Assigned Actions</th>
        <th class="permission-manager-table-header">Available Actions (Not Assigned)</th>
        <th class="permission-manager-table-header">update</th>
        <th class="permission-manager-table-header">delete</th>
    </tr>
    <?php
    foreach ($permissionCrud->readAll() as $permission) {
        ?>
        <tr class="permission-manager-table-row">
            <td id="<?php echo trim(str_replace(' ', '', $permission->getPermissionName())); ?>-permission-name"
                class="permission-manager-table-permission-name"><?php
                $permissionNameInput = new \DarlingCms\classes\html\form\Text('permissionName', $permission->getPermissionName(), ['id' => 'permissionNameFormElement', 'class' => 'dcms-input-text dcms-focus dcms-hover permission-manager-input-text']);
                echo $permissionNameInput->getHtml();
                ?>
            </td>
            <td id="<?php echo trim(str_replace(' ', '', $permission->getPermissionName())); ?>-permission-assigned-actions"
                class="permission-manager-table-permission-assigned-actions">
                <?php
                $actionCheckboxes = '';
                foreach ($permission->getActions() as $action) {
                    $actionCheckbox = new \DarlingCms\classes\html\form\Checkbox($action->getActionName() . '-checkbox', $action->getActionName(), ['class' => 'dcms-input-checkbox dcms-focus dcms-hover permission-manager-input-checkbox']);
                    echo $actionCheckbox->getHtml();
                }
                //var_dump($actionCheckboxes);
                //$permissionAssignedActions = new \DarlingCms\classes\html\form\TextArea('permissionAssignedActions', ['id' => 'permissionAssignedActionsFormElement', 'class' => 'dcms-input-textarea dcms-focus dcms-hover permission-manager-input-textarea'], $actionCheckboxes);
                //echo $permissionAssignedActions->getHtml();
                ?>
            </td>
            <td>
                No Info
            </td>
            <td class="permission-manager-table-update-permission">
                <?php
                $updateAjaxReq = \DarlingCms\abstractions\userInterface\AjaxUi::generateAjaxRequest([
                    'issuingApp' => 'PermissionManager',
                    'handlerName' => 'updatePermissionHandler',
                    'outputElementId' => 'PermissionManagerView',
                    'requestType' => 'POST',
                    'contentType' => '',
                    // 'additionalParams' => 'permissionName=\'+this.dataset.permissionName+\'' . '&' . 'permissionDescription=\'+this.dataset.permissionDescription+\'',// @todo this should actually reference sybilings to get text and textarea values @see https://www.w3schools.com/jsref/tryit.asp?filename=tryjsref_node_previoussibling
                    'additionalParams' => 'originalPermissionName=\'+this.dataset.permissionName+\'' . '&' . 'originalPermissionDescription=\'+this.dataset.permissionDescription+\'' . '&' . 'permissionName=\'+this.parentNode.parentNode.children[0].children[0].value+\'' . '&' . 'permissionDescription=\'+this.parentNode.parentNode.children[1].children[0].value+\'',
                    'ajaxDirName' => 'handlers',
                    'callFunction' => '',
                    'callContext' => '',
                    'callArgs' => ''
                ]);
                //$updateButton = new \DarlingCms\classes\html\HtmlTag('button', ['onclick' => 'confirm(\'Are you sure you want to update the ' . $permission->getPermissionName() . ' permission?\') === true ? ' . $updateAjaxReq . ' : console.log(\'Canceled request to update the ' . $permission->getPermissionName() . ' permission.\')', 'data-permission-name' => $permission->getPermissionName(), 'data-permission-description' => $permission->getPermissionDescription(), 'class' => 'dcms-button permission-manager-update-permission-button'], 'update Permission');
                //echo $updateButton->getHtml();
                ?>
            </td>
            <td class="permission-manager-table-delete-permission">
                <?php
                $deleteAjaxReq = \DarlingCms\abstractions\userInterface\AjaxUi::generateAjaxRequest([
                    'issuingApp' => 'PermissionManager',
                    'handlerName' => 'deletePermissionHandler',
                    'outputElementId' => 'PermissionManagerView',
                    'requestType' => 'POST',
                    'contentType' => '',
                    'additionalParams' => 'permissionName=\'+this.dataset.permissionName+\'',// @todo this should actually reference sybilings to get text and textarea values @see https://www.w3schools.com/jsref/tryit.asp?filename=tryjsref_node_previoussibling
                    'ajaxDirName' => 'handlers',
                    'callFunction' => '',
                    'callContext' => '',
                    'callArgs' => ''
                ]);
                $deleteButton = new \DarlingCms\classes\html\HtmlTag('button', ['onclick' => 'confirm(\'Are you sure you want to delete the ' . $permission->getPermissionName() . ' permission?\') === true ? ' . $deleteAjaxReq . ' : console.log(\'Canceled request to delete the ' . $permission->getPermissionName() . ' permission.\')', 'data-permission-name' => $permission->getPermissionName(), 'class' => 'dcms-button permission-manager-delete-permission-button'], 'Delete Permission');
                echo $deleteButton->getHtml();
                ?>
            </td>
        </tr>
        <?php
    }
    ?>
</table>

