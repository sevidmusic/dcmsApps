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
$action = new \DarlingCms\classes\privilege\Action(filter_input(INPUT_POST, 'actionName'), filter_input(INPUT_POST, 'actionDescription'));
$actionCrud->create($action);
?>

<p>Created new action <?php echo filter_input(INPUT_POST, 'actionName'); ?></p>
