<?php

use \DarlingCms\classes\staticClasses\core\CoreValues;

if (filter_input(INPUT_POST, 'ajaxRequest') === 'true') {
    require str_replace('/apps/ActionManager/handlers', '/vendor/autoload.php', __DIR__);
}

$sqlQuery = \DarlingCms\classes\staticClasses\core\CoreMySqlQuery::DbConnection(CoreValues::PRIVILEGES_DB_NAME);
$actionCrud = new \DarlingCms\classes\crud\MySqlActionCrud($sqlQuery);
$action = new \DarlingCms\classes\privilege\Action(filter_input(INPUT_POST, 'actionName'), filter_input(INPUT_POST, 'actionDescription'));
if ($actionCrud->create($action) === true) {
    echo '<p class="dcms-positive-text">Created new action ' . filter_input(INPUT_POST, 'actionName') . '</p>';
} else {
    echo '<p class="dcms-negative-text">An error occurred and the new ' . filter_input(INPUT_POST, 'actionName') . ' action could not be created</p>';
}
