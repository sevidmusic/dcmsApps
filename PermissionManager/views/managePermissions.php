<?php

use \DarlingCms\classes\staticClasses\core\CoreValues;

if (filter_input(INPUT_GET, 'ajaxRequest') === 'true') {
    require str_replace('/apps/PermissionManager/views', '/vendor/autoload.php', __DIR__);
}
$crudFactory = new \DarlingCms\classes\factory\CoreMySqlCrudFactory();
$actionCrud = $crudFactory->getActionCrud();
$permissionCrud = $crudFactory->getPermissionCrud();
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
        $permissionElementIdPrefix = trim(str_replace(' ', '', $permission->getPermissionName()));
        $permissionNameElementId = $permissionElementIdPrefix . 'PermissionNameFormElement';
        ?>
        <tr class="permission-manager-table-row">
            <td id="<?php echo $permissionElementIdPrefix; ?>-permission-name"
                class="permission-manager-table-permission-name">
                <div class="permission-manager-table-cell-content-container">
                    <?php
                    $permissionNameInput = new \DarlingCms\classes\html\form\Text('permissionName', $permission->getPermissionName(), ['id' => $permissionNameElementId, 'class' => 'dcms-input-text dcms-focus dcms-hover permission-manager-input-text', 'disabled']); // @todo ! *ACTIVE* Need to figure out how to let roles that use this permission know when the permission's name is changed and update accordingly.*EOL*NOTE:*EOL*- This MUST not create new dependency between the relevant objects*EOL*- This MUST not effect the app's current functional state as it is at present, i.e. MUST not introduce new bugs
                    echo $permissionNameInput->getHtml();
                    ?>
                </div>
            </td>
            <td id="<?php echo $permissionElementIdPrefix; ?>-permission-assigned-actions"
                class="permission-manager-table-permission-assigned-actions">
                <div class="permission-manager-table-cell-content-container">
                    <?php
                    $assignedActionNames = array();
                    $assignedActionIncrementer = 0;
                    foreach ($permission->getActions() as $action) {
                        array_push($assignedActionNames, $action->getActionName());
                        $actionCheckbox = new \DarlingCms\classes\html\form\Checkbox($action->getActionName() . '-checkbox', $action->getActionName(), ['id' => $permissionElementIdPrefix . 'AssignedActionCheckbox' . strval($assignedActionIncrementer), 'checked', 'class' => 'dcms-input-checkbox dcms-focus dcms-hover permission-manager-input-checkbox']);
                        echo '<div title="Un-check to un-assign..." class="permission-manager-assigned-action-checkbox">' . $actionCheckbox->getHtml() . $action->getActionName() . '</div>';
                        $assignedActionIncrementer++;
                    }
                    ?>
                </div>
            </td>
            <td>
                <div class="permission-manager-table-cell-content-container">
                    <?php
                    $unAssignedActionNames = array();
                    $unAssignedActionIncrementer = 0;
                    foreach ($actionCrud->readAll() as $action) {
                        if (!in_array($action->getActionName(), $assignedActionNames, true) === true) {
                            array_push($unAssignedActionNames, $action->getActionName());
                            $actionCheckbox = new \DarlingCms\classes\html\form\Checkbox($action->getActionName() . '-checkbox', $action->getActionName(), ['id' => $permissionElementIdPrefix . 'UnAssignedActionCheckbox' . strval($unAssignedActionIncrementer), 'class' => 'dcms-input-checkbox dcms-focus dcms-hover permission-manager-input-checkbox']);
                            echo '<div title="Check to assign..." class="permission-manager-available-action-checkbox">' . $actionCheckbox->getHtml() . $action->getActionName() . '</div>';
                            $unAssignedActionIncrementer++;
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
                    $assignedActionParamStr = '';
                    for ($i = 0; $i <= (count($assignedActionNames) - 1); $i++) {
                        $assignedActionTargetId = $permissionElementIdPrefix . 'AssignedActionCheckbox' . $i;
                        $assignedActionParamStr .= '&' . 'assignedActionNames[]=\'+getElementValue(\'' . $assignedActionTargetId . '\')+\'' . '&' . 'assignedActionStates[]=\'+checkboxIsChecked(\'' . $assignedActionTargetId . '\')+\'';
                    }
                    $unAssignedActionParamStr = '';
                    for ($i = 0; $i <= (count($unAssignedActionNames) - 1); $i++) {
                        $unAssignedActionTargetId = $permissionElementIdPrefix . 'UnAssignedActionCheckbox' . $i;
                        $unAssignedActionParamStr .= '&' . 'unAssignedActionNames[]=\'+getElementValue(\'' . $unAssignedActionTargetId . '\')+\'' . '&' . 'unAssignedActionStates[]=\'+checkboxIsChecked(\'' . $unAssignedActionTargetId . '\')+\'';
                    }

                    $updateAjaxReq = \DarlingCms\abstractions\userInterface\AjaxUi::generateAjaxRequest([
                        'issuingApp' => 'PermissionManager',
                        'handlerName' => 'updatePermissionHandler',
                        'outputElementId' => 'PermissionManagerView',
                        'requestType' => 'POST',
                        'contentType' => '',
                        'additionalParams' => 'originalPermissionName=\'+this.dataset.permissionName+\'' . '&' . 'permissionName=\'+getElementValue(\'' . $permissionNameElementId . '\')+\'' . $assignedActionParamStr . $unAssignedActionParamStr,
                        'ajaxDirName' => 'handlers',
                        'callFunction' => '',
                        'callContext' => '',
                        'callArgs' => ''
                    ]);
                    $updateButton = new \DarlingCms\classes\html\HtmlTag('button', ['onclick' => 'confirm(\'Are you sure you want to update the ' . $permission->getPermissionName() . ' permission?\') === true ? ' . $updateAjaxReq . ' : console.log(\'Canceled request to update the ' . $permission->getPermissionName() . ' permission.\')', 'data-permission-name' => $permission->getPermissionName(), 'class' => 'dcms-button permission-manager-update-permission-button'], 'Update Permission');
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
                    $deleteButton = new \DarlingCms\classes\html\HtmlTag('button', ['onclick' => 'confirm(\'Are you sure you want to delete the ' . $permission->getPermissionName() . ' permission?\') === true ? ' . $deleteAjaxReq . ' : console.log(\'Canceled request to delete the ' . $permission->getPermissionName() . ' permission.\')', 'data-permission-name' => $permission->getPermissionName(), 'class' => 'dcms-button permission-manager-delete-permission-button'], 'Delete Permission');
                    echo $deleteButton->getHtml();
                    //$tempButton = new \DarlingCms\classes\html\HtmlTag('button', ['style' => 'cursor: not-allowed;', 'class' => 'dcms-button permission-manager-update-permission-button'], 'Not Available!');
                    //echo $tempButton->getHtml();
                    ?>
                </div>
            </td>
        </tr>
        <?php
    }
    ?>
</table>

