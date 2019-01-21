<?php

use \DarlingCms\classes\staticClasses\core\CoreValues;

if (filter_input(INPUT_POST, 'ajaxRequest') === 'true') {
    require str_replace('/apps/ActionManager/handlers', '/vendor/autoload.php', __DIR__);
}

$sqlQuery = CoreValues::getISqlQueryInstance
(
    CoreValues::CORE_DB_HOST,
    CoreValues::CORE_DB_NAME,
    'root',
    'root'
);
$actionCrud = new \DarlingCms\classes\crud\MySqlActionCrud($sqlQuery);
$originalActionName = filter_input(INPUT_POST, 'originalActionName');
$originalActionDescription = filter_input(INPUT_POST, 'originalActionDescription');
$newActionName = filter_input(INPUT_POST, 'actionName');
$newActionDescription = filter_input(INPUT_POST, 'actionDescription');
// @devNote usiing var for readability...this could be passed directly to if()
$changesDetected = !($originalActionName === $newActionName && $originalActionDescription === $newActionDescription);
if ($changesDetected === true && $actionCrud->update($originalActionName, new \DarlingCms\classes\privilege\Action($newActionName, $newActionDescription)) === true) {
    ?>
    <div class="action-manager-updated-action-info">
        <p>The Action was updated successfully</p>
        <table style="width:100%;padding: 20px;border: 1px solid #ffffff;"
               class="action-manager-original-action-info-table">
            <tr>
                <th>Original Name:</th>
                <th>Original Description:</th>
            </tr>
            <tr>
                <td style="padding: 20px;border: 1px solid #8EA3B0;"><?php echo $originalActionName; ?></td>
                <td style="padding: 20px;border: 1px solid #8EA3B0;"><?php echo $originalActionDescription; ?></td>
            </tr>
        </table>
        <table style="width:100%;padding: 20px;border: 1px solid #ffffff;"
               class="action-manager-updated-action-info-table">
            <tr>
                <th>New Name:</th>
                <th>New Description:</th>
            </tr>
            <tr>
                <td style="padding: 20px;border: 1px solid #8EA3B0;"><?php echo $newActionName; ?></td>
                <td style="padding: 20px;border: 1px solid #8EA3B0;"><?php echo $newActionDescription; ?></td>
            </tr>
        </table>
    </div>
    <?php
} else {
    ?>
    <p>The action was not updated, either no changes were detected, or an error occurred.</p>
    <?php
}

