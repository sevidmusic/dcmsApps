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
$role = new \DarlingCms\classes\privilege\Role(filter_input(INPUT_POST, 'roleName'), filter_input(INPUT_POST, 'roleDescription'));
$roleCrud->create($role);
?>

<p>Created new role "Some Role"</p>
