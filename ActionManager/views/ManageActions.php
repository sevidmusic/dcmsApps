<?php

use \DarlingCms\classes\staticClasses\core\CoreValues;

if (filter_input(INPUT_GET, 'ajaxRequest') === 'true') {
    require str_replace('/apps/ActionManager/views', '/vendor/autoload.php', __DIR__);
}
$sqlQuery = CoreValues::getMySqlQueryInstance
(
    CoreValues::CORE_DB_HOST,
    CoreValues::CORE_DB_NAME,
    'root',
    'root'
);
$actionCrud = new \DarlingCms\classes\crud\MySqlActionCrud($sqlQuery);
?>
<h1>Manage Actions</h1>
<table class="action-manager-table">
    <tr class="action-manager-table-header-row">
        <th class="action-manager-table-header">Name</th>
        <th class="action-manager-table-header">Description</th>
        <th class="action-manager-table-header"></th>
        <th class="action-manager-table-header"></th>
    </tr>
    <?php
    foreach ($actionCrud->readAll() as $action) {
        $actionElementIdPrefix = str_replace(' ', '', $action->getActionName());
        $actionNameFormElementId = $actionElementIdPrefix . 'ActionNameFormElement';
        $actionDescriptionFormElementId = $actionElementIdPrefix . 'ActionDescriptionFormElement';
        ?>
        <tr class="action-manager-table-row">
            <td id="<?php echo trim($actionElementIdPrefix); ?>-action-name"
                class="action-manager-table-action-name"><?php
                $actionNameInput = new \DarlingCms\classes\html\form\Text('actionName', $action->getActionName(), ['id' => $actionNameFormElementId, 'class' => 'dcms-input-text dcms-focus dcms-hover action-manager-input-text', 'disabled']); // @todo ! *ACTIVE* Need to figure out how to let permissions that use this action know when the permission's name is changed and update accordingly.*EOL*NOTE:*EOL*- This MUST not create new dependency between the relevant objects*EOL*- This MUST not effect the app's current functional state as it is at present, i.e. MUST not introduce new bugs
                echo $actionNameInput->getHtml();
                ?></td>
            <td id="<?php echo trim($actionElementIdPrefix); ?>-action-description"
                class="action-manager-table-action-description">
                <?php
                $actionDescriptionInput = new \DarlingCms\classes\html\form\TextArea('actionDescription', ['id' => $actionDescriptionFormElementId, 'class' => 'dcms-input-textarea dcms-focus dcms-hover action-manager-input-textarea'], $action->getActionDescription());
                echo $actionDescriptionInput->getHtml();
                ?>
            </td>
            <td class="action-manager-table-save-changes">
                <?php
                $updateAjaxReq = \DarlingCms\abstractions\userInterface\AjaxUi::generateAjaxRequest([
                    'issuingApp' => 'ActionManager',
                    'handlerName' => 'updateActionHandler',
                    'outputElementId' => 'ActionManagerView',
                    'requestType' => 'POST',
                    'contentType' => '',
                    'additionalParams' => 'originalActionName=\'+this.dataset.actionName+\'' . '&' . 'originalActionDescription=\'+this.dataset.actionDescription+\'' . '&' . 'actionName=\'+getElementValue(\'' . $actionNameFormElementId . '\')+\'' . '&' . 'actionDescription=\'+getElementValue(\'' . $actionDescriptionFormElementId . '\')+\'',
                    'ajaxDirName' => 'handlers',
                    'callFunction' => '',
                    'callContext' => '',
                    'callArgs' => ''
                ]);
                $updateButton = new \DarlingCms\classes\html\HtmlTag('button', ['onclick' => 'confirm(\'Are you sure you want to update the ' . $action->getActionName() . ' action?\') === true ? ' . $updateAjaxReq . ' : console.log(\'Canceled request to update the ' . $action->getActionName() . ' action.\')', 'data-action-name' => $action->getActionName(), 'data-action-description' => $action->getActionDescription(), 'class' => 'dcms-button action-manager-update-action-button'], 'Update Action');
                echo $updateButton->getHtml();
                ?>
            </td>
            <td class="action-manager-table-delete-action">
                <?php
                $deleteAjaxReq = \DarlingCms\abstractions\userInterface\AjaxUi::generateAjaxRequest([
                    'issuingApp' => 'ActionManager',
                    'handlerName' => 'deleteActionHandler',
                    'outputElementId' => 'ActionManagerView',
                    'requestType' => 'POST',
                    'contentType' => '',
                    'additionalParams' => 'actionName=\'+this.dataset.actionName+\'',// @todo this should actually reference sybilings to get text and textarea values @see https://www.w3schools.com/jsref/tryit.asp?filename=tryjsref_node_previoussibling
                    'ajaxDirName' => 'handlers',
                    'callFunction' => '',
                    'callContext' => '',
                    'callArgs' => ''
                ]);
                $deleteButton = new \DarlingCms\classes\html\HtmlTag('button', ['onclick' => 'confirm(\'Are you sure you want to delete the ' . $action->getActionName() . ' action?\') === true ? ' . $deleteAjaxReq . ' : console.log(\'Canceled request to delete the ' . $action->getActionName() . ' action.\')', 'data-action-name' => $action->getActionName(), 'class' => 'dcms-button action-manager-delete-action-button'], 'Delete Action');
                echo $deleteButton->getHtml();
                ?>
            </td>
        </tr>
        <?php
    }
    ?>
</table>

