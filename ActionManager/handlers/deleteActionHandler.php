<?php

use \DarlingCms\classes\staticClasses\core\CoreValues;

if (filter_input(INPUT_POST, 'ajaxRequest') === 'true') {
    require str_replace('/apps/ActionManager/handlers', '/vendor/autoload.php', __DIR__);
}

$sqlQuery = \DarlingCms\classes\staticClasses\core\CoreMySqlQuery::DbConnection(CoreValues::PRIVILEGES_DB_NAME);
$actionCrud = new \DarlingCms\classes\crud\MySqlActionCrud($sqlQuery);
if ($actionCrud->delete(filter_input(INPUT_POST, 'actionName')) === true) {
    ?>
    <p class="dcms-positive-text">The <?php echo filter_input(INPUT_POST, 'actionName'); ?> action was deleted
        successfully.</p>
    <?php
} else {
    ?>
    <p class="dcms-negative-text">An error occurred and the <?php echo filter_input(INPUT_POST, 'actionName'); ?> action
        could not be deleted.</p>
    <?php
}
