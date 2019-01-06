<?php

use \DarlingCms\classes\staticClasses\core\CoreValues;

if (filter_input(INPUT_POST, 'ajaxRequest') === 'true') {
    require str_replace('/apps/RoleManager/handlers', '/vendor/autoload.php', __DIR__);
}

$sqlQuery = CoreValues::getISqlQueryInstance
(
    CoreValues::CORE_DB_HOST,
    CoreValues::CORE_DB_NAME,
    'root',
    'root'
);
$roleCrud = new \DarlingCms\classes\crud\MySqlRoleCrud($sqlQuery);
if ($roleCrud->delete(filter_input(INPUT_POST, 'roleName')) === true) {
    ?>
    <p class="dcms-positive-text">Deleted Role <?php echo filter_input(INPUT_POST, 'roleName'); ?></p>
    <?php
} else {
    ?>
    <p class="dcms-negative-text">An error occurred and the <?php echo filter_input(INPUT_POST, 'roleName'); ?> role
        could not be deleted.</p>
    <?php
}
