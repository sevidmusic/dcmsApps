<?php

use \DarlingCms\classes\staticClasses\core\CoreValues;

require str_replace('/apps/PermissionManager/views', '/vendor/autoload.php', __DIR__);

$crudFactory = new \DarlingCms\classes\factory\CoreMySqlCrudFactory();
$actionCrud = $crudFactory->getActionCrud();
$permissionCrud = $crudFactory->getPermissionCrud();?>
<div class="permission-manager-create-permission-container">
    <?php
    $permission = new \DarlingCms\classes\privilege\Permission('', array());
    $permissionElementIdPrefix = trim(str_replace(' ', '', $permission->getPermissionName()));
    $permissionNameElementId = $permissionElementIdPrefix . 'PermissionNameFormElement';
    ?>
    <?php
    $assignedActionNames = array();
    foreach ($permission->getActions() as $action) {
        array_push($assignedActionNames, $action->getActionName());
    }
    ?>
    <div>
        <div id="<?php echo $permissionElementIdPrefix; ?>-permission-name"
             class="permission-manager-permission-name">
            <div class="permission-manager-sub-content-container">
                <h3>Enter a name for the new Permission</h3>
                <?php
                $permissionNameInput = new \DarlingCms\classes\html\form\Text('permissionName', $permission->getPermissionName(), ['id' => $permissionNameElementId, 'class' => 'dcms-input-text dcms-focus dcms-hover permission-manager-input-text']);
                echo $permissionNameInput->getHtml();
                ?>
            </div>
        </div>
        <div class="permission-manager-create-permission-available-actions">
            <div class="permission-manager-sub-content-container">
                <h3>Select the actions that should be assigned to the new Permission</h3>
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
        </div>
        <div class="permission-manager-create-permission">
            <div class="permission-manager-sub-content-container">
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

                $createAjaxReq = \DarlingCms\abstractions\userInterface\AjaxUI::generateAjaxRequest([
                    'issuingApp' => 'PermissionManager',
                    'handlerName' => 'createPermissionHandler',
                    'outputElementId' => 'PermissionManagerView',
                    'requestType' => 'POST',
                    'contentType' => '',
                    'additionalParams' => 'originalPermissionName=\'+this.dataset.permissionName+\'' . '&' . 'permissionName=\'+getElementValue(\'' . $permissionNameElementId . '\')+\'' . $assignedActionParamStr . $unAssignedActionParamStr,
                    'ajaxDirName' => 'handlers',
                    'callFunction' => '',
                    'callContext' => '',
                    'callArgs' => ''
                ]);
                $createButton = new \DarlingCms\classes\html\HtmlTag('button', ['onclick' => 'confirm(\'Are you sure you want to create the ' . $permission->getPermissionName() . ' permission?\') === true ? ' . $createAjaxReq . ' : console.log(\'Canceled request to create the ' . $permission->getPermissionName() . ' permission.\')', 'data-permission-name' => $permission->getPermissionName(), 'class' => 'dcms-button permission-manager-create-permission-button'], 'Create New Permission');
                echo $createButton->getHtml();
                ?>
            </div>
        </div>
    </div>
</div>
