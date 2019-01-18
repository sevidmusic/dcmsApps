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
        <th class="permission-manager-table-header"></th>
        <th class="permission-manager-table-header"></th>
    </tr>
    <?php
    foreach ($permissionCrud->readAll() as $permission) {
        ?>
        <tr class="permission-manager-table-row">
            <td id="<?php echo trim(str_replace(' ', '', $permission->getPermissionName())); ?>-permission-name"
                class="permission-manager-table-permission-name">
                <div class="permission-manager-table-cell-content-container">
                    <?php
                    $permissionNameInput = new \DarlingCms\classes\html\form\Text('permissionName', $permission->getPermissionName(), ['id' => 'permissionNameFormElement', 'class' => 'dcms-input-text dcms-focus dcms-hover permission-manager-input-text']);
                    echo $permissionNameInput->getHtml();
                    ?>
                </div>
            </td>
            <td id="<?php echo trim(str_replace(' ', '', $permission->getPermissionName())); ?>-permission-assigned-actions"
                class="permission-manager-table-permission-assigned-actions">
                <div class="permission-manager-table-cell-content-container">
                    <?php
                    $assignedActionNames = array();
                    foreach ($permission->getActions() as $action) {
                        array_push($assignedActionNames, $action->getActionName());
                        $actionCheckbox = new \DarlingCms\classes\html\form\Checkbox($action->getActionName() . '-checkbox', $action->getActionName(), ['checked', 'class' => 'dcms-input-checkbox dcms-focus dcms-hover permission-manager-input-checkbox']);
                        echo '<div title="Un-check to un-assign..." class="permission-manager-assigned-action-checkbox">' . $actionCheckbox->getHtml() . $action->getActionName() . '</div>';
                    }
                    ?>
                </div>
            </td>
            <td>
                <div class="permission-manager-table-cell-content-container">
                    <?php
                    $actionCrud = new \DarlingCms\classes\crud\MySqlActionCrud($sqlQuery);
                    foreach ($actionCrud->readAll() as $action) {
                        if (!in_array($action->getActionName(), $assignedActionNames, true) === true) {
                            $actionCheckbox = new \DarlingCms\classes\html\form\Checkbox($action->getActionName() . '-checkbox', $action->getActionName(), ['class' => 'dcms-input-checkbox dcms-focus dcms-hover permission-manager-input-checkbox']);
                            echo '<div title="Check to assign..." class="permission-manager-available-action-checkbox">' . $actionCheckbox->getHtml() . $action->getActionName() . '</div>';
                        }
                    }
                    ?>
                </div>
            </td>
            <td class="permission-manager-table-update-permission">
                <div class="permission-manager-table-cell-content-container">
                    <?php
                    //
                    // 1. Build param string from assignedActionNames, we dont need unassigned as any unchecked will be turned off.
                    // 2. append constructed param string to additionalParams string
                    //
                    $assignedActionNamesStr = '';
                    foreach ($assignedActionNames as $assignedActionName) {
                        $assignedActionNamesStr .= '&assignedActions[]=' . $assignedActionName;
                    }
                    $updateAjaxReq = \DarlingCms\abstractions\userInterface\AjaxUi::generateAjaxRequest([
                        'issuingApp' => 'PermissionManager',
                        'handlerName' => 'updatePermissionHandler',
                        'outputElementId' => 'PermissionManagerView',
                        'requestType' => 'POST',
                        'contentType' => '',
                        'additionalParams' => 'originalPermissionName=\'+this.dataset.permissionName+\'' . '&' . 'permissionName=\'+this.parentNode.parentNode.parentNode.children[0].children[0].children[0].value+\'' . '&' . 'ACTION_NAME=\'+this.parentNode.parentNode.parentNode.children[1].children[0].children[0].children[0].checked+\'',// . $assignedActionNamesStr,
                        'ajaxDirName' => 'handlers',
                        'callFunction' => '',
                        'callContext' => '',
                        'callArgs' => ''
                    ]);
                    $updateButton = new \DarlingCms\classes\html\HtmlTag('button', ['onclick' => 'confirm(\'Are you sure you want to update the ' . $permission->getPermissionName() . ' permission?\') === true ? ' . $updateAjaxReq . ' : console.log(\'Canceled request to update the ' . $permission->getPermissionName() . ' permission.\')', 'data-permission-name' => $permission->getPermissionName(), 'class' => 'dcms-button permission-manager-update-permission-button'], 'update Permission');
                    echo $updateButton->getHtml();
                    ?>
                </div>
            </td>
            <td class="permission-manager-table-delete-permission">
                <div class="permission-manager-table-cell-content-container">
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
                    //$deleteButton = new \DarlingCms\classes\html\HtmlTag('button', ['onclick' => 'confirm(\'Are you sure you want to delete the ' . $permission->getPermissionName() . ' permission?\') === true ? ' . $deleteAjaxReq . ' : console.log(\'Canceled request to delete the ' . $permission->getPermissionName() . ' permission.\')', 'data-permission-name' => $permission->getPermissionName(), 'class' => 'dcms-button permission-manager-delete-permission-button'], 'Delete Permission');
                    //echo $deleteButton->getHtml();
                    $tempButton = new \DarlingCms\classes\html\HtmlTag('button', ['style' => 'cursor: not-allowed;', 'class' => 'dcms-button permission-manager-update-permission-button'], 'Not Available!');
                    echo $tempButton->getHtml();
                    ?>
                </div>
            </td>
        </tr>
        <?php
    }
    ?>
</table>

