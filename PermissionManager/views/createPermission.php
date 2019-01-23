<?php

use \DarlingCms\classes\staticClasses\core\CoreValues;

require str_replace('/apps/PermissionManager/views', '/vendor/autoload.php', __DIR__);

$sqlQuery = CoreValues::getISqlQueryInstance
(
    CoreValues::CORE_DB_HOST,
    CoreValues::CORE_DB_NAME,
    'root',
    'root'
);
$actionCrud = new \DarlingCms\classes\crud\MySqlActionCrud($sqlQuery);
$permissionCrud = new \DarlingCms\classes\crud\MySqlPermissionCrud($sqlQuery, $actionCrud);
$post = filter_input_array(INPUT_POST);

$permissionNameInput = new \DarlingCms\classes\html\form\Input('text', 'newPermissionName', '', ['id' => 'permission-manager-permission-name-input', 'class' => 'dcms-input-text'], true);
$createPermissionButton = new \DarlingCms\classes\html\HtmlTag('button', ['id' => 'creatPermissionButton', 'onclick' => 'alert(\'Saving Permission\')'], 'Create Permission');
echo $permissionNameInput->getHtml();
echo $createPermissionButton->getHtml();
