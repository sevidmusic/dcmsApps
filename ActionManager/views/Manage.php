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
<table>
    <tr>
        <th>Name</th>
        <th>Description</th>
    </tr>
    <tr>
        <td><?php
            $actionNameInput = new \DarlingCms\classes\html\form\Text('actionName', '', ['id' => 'actionNameFormElement', 'class' => 'dcms-input-text dcms-focus dcms-hover']);
            echo $actionNameInput->getHtml();
            ?></td>
        <td>
            <?php
            $actionDescriptionInput = new \DarlingCms\classes\html\form\TextArea('actionDescription', ['id' => 'actionDescriptionFormElement', 'class' => 'dcms-input-textarea dcms-focus dcms-hover']);
            echo $actionDescriptionInput->getHtml();
            ?>
        </td>
    </tr>
</table>
