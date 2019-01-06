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
$originalRoleName = filter_input(INPUT_POST, 'originalRoleName');
$originalRoleDescription = filter_input(INPUT_POST, 'originalRoleDescription');
$newRoleName = filter_input(INPUT_POST, 'roleName');
$newRoleDescription = filter_input(INPUT_POST, 'roleDescription');
// @devNote usiing var for readability...this could be passed directly to if()
$changesDetected = !($originalRoleName === $newRoleName && $originalRoleDescription === $newRoleDescription);
if ($changesDetected === true && $roleCrud->update($originalRoleName, new \DarlingCms\classes\privilege\Role($newRoleName, $newRoleDescription)) === true) {
    ?>
    <div class="role-manager-updated-role-info">
        <p>The Role was updated successfully</p>
        <table style="width:100%;padding: 20px;border: 1px solid #ffffff;"
               class="role-manager-original-role-info-table">
            <tr>
                <th>Original Name:</th>
                <th>Original Description:</th>
            </tr>
            <tr>
                <td style="padding: 20px;border: 1px solid #8EA3B0;"><?php echo $originalRoleName; ?></td>
                <td style="padding: 20px;border: 1px solid #8EA3B0;"><?php echo $originalRoleDescription; ?></td>
            </tr>
        </table>
        <table style="width:100%;padding: 20px;border: 1px solid #ffffff;"
               class="role-manager-updated-role-info-table">
            <tr>
                <th>New Name:</th>
                <th>New Description:</th>
            </tr>
            <tr>
                <td style="padding: 20px;border: 1px solid #8EA3B0;"><?php echo $newRoleName; ?></td>
                <td style="padding: 20px;border: 1px solid #8EA3B0;"><?php echo $newRoleDescription; ?></td>
            </tr>
        </table>
    </div>
    <?php
} else {
    ?>
    <p>The role was not updated, either no changes were detected, or an error occurred.</p>
    <?php
}

