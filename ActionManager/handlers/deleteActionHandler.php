<?php

use \DarlingCms\classes\staticClasses\core\CoreValues;

if (filter_input(INPUT_POST, 'ajaxRequest') === 'true') {
    require str_replace('/apps/ActionManager/handlers', '/vendor/autoload.php', __DIR__);
}

$sqlQuery = CoreValues::getMySqlQueryInstance
(
    CoreValues::CORE_DB_HOST,
    CoreValues::CORE_DB_NAME,
    'root',
    'root'
);
$actionCrud = new \DarlingCms\classes\crud\MySqlActionCrud($sqlQuery);
if ($actionCrud->delete(filter_input(INPUT_POST, 'actionName')) === true) {
    ?>
    <p class="dcms-positive-text">Deleted Action <?php echo filter_input(INPUT_POST, 'actionName'); ?></p>
    <?php
} else {
    ?>
    <p class="dcms-negative-text">An error occurred and the <?php echo filter_input(INPUT_POST, 'actionName'); ?> action
        could not be deleted.</p>
    <?php
}
