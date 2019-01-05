<?php

use \DarlingCms\classes\staticClasses\core\CoreValues;

if (filter_input(INPUT_GET, 'ajaxRequest') === 'true') {
    require str_replace('/apps/ActionManager/views', '/vendor/autoload.php', __DIR__);
}
$sqlQuery = CoreValues::getISqlQueryInstance
(
    CoreValues::CORE_DB_HOST,
    CoreValues::CORE_DB_NAME,
    'root',
    'root'
);
$actionCrud = new \DarlingCms\classes\crud\MySqlActionCrud($sqlQuery);
?>
<h1>Manage Actions</h1>
<table class="action-manager-table"
<tr class="action-manager-table-header-row">
    <th class="action-manager-table-header">Name</th>
    <th class="action-manager-table-header">Description</th>
    <th class="action-manager-table-header"></th>
    <th class="action-manager-table-header"></th>
</tr>
<?php
foreach ($actionCrud->readAll() as $action) {
    ?>
    <tr class="action-manager-table-row">
        <td class="action-manager-table-action-name"><?php
            $actionNameInput = new \DarlingCms\classes\html\form\Text('actionName', $action->getActionName(), ['id' => 'actionNameFormElement', 'class' => 'dcms-input-text dcms-focus dcms-hover action-manager-input-text']);
            echo $actionNameInput->getHtml();
            ?></td>
        <td class="action-manager-table-action-description">
            <?php
            $actionDescriptionInput = new \DarlingCms\classes\html\form\TextArea('actionDescription', ['id' => 'actionDescriptionFormElement', 'class' => 'dcms-input-textarea dcms-focus dcms-hover action-manager-input-textarea'], $action->getActionDescription());
            echo $actionDescriptionInput->getHtml();
            ?>
        </td>
        <td class="action-manager-table-save-changes">
            <?php
            $saveButton = new \DarlingCms\classes\html\HtmlTag('button', ['class' => 'dcms-button action-manager-save-changes-button'], 'Save Changes');
            echo $saveButton->getHtml();
            ?>
        </td>
        <td class="action-manager-table-delete-action">
            <?php
            $saveButton = new \DarlingCms\classes\html\HtmlTag('button', ['class' => 'dcms-button action-manager-delete-action-button'], 'Delete Action');
            echo $saveButton->getHtml();
            ?>
        </td>
    </tr>
    <?php
}
?>
</table>

