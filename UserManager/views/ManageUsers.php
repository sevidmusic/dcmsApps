<?php

if (filter_input(INPUT_GET, 'ajaxRequest') === 'true') {
    require str_replace('/apps/UserManager/views', '/vendor/autoload.php', __DIR__);
}
$coreCrud = new \DarlingCms\classes\factory\CoreMySqlCrudFactory();
$actionCrud = $coreCrud->getActionCrud();
$permissionCrud = $coreCrud->getPermissionCrud();
$roleCrud = $coreCrud->getRoleCrud();
$userCrud = $coreCrud->getUserCrud();

?>
<h1>Manage Users</h1>
<table class="user-manager-table">
    <tr class="user-manager-table-header-row">
        <th class="user-manager-table-header">Name</th>
        <th class="user-manager-table-header">Assigned Roles</th>
        <th class="user-manager-table-header">Available Roles (Not assigned)</th>
        <th class="user-manager-table-header">Public Meta Data</th>
        <th class="user-manager-table-header">Private Meta Data</th>
        <th class="user-manager-table-header"></th>
        <th class="user-manager-table-header"></th>
    </tr>
    <?php
    foreach ($userCrud->readAll() as $user) {
        $userElementIdPrefix = str_replace(' ', '', $user->getUserName());
        $userNameElementId = $userElementIdPrefix . 'UserNameElement';
        ?>
        <tr class="user-manager-table-row">
            <td id="<?php echo trim(str_replace(' ', '', $user->getUserName())); ?>-user-name"
                class="user-manager-table-user-name">
                <div class="user-manager-table-cell-content-container">
                    <?php
                    $userNameInput = new \DarlingCms\classes\html\form\Text('userName', $user->getUserName(), ['id' => $userNameElementId, 'class' => 'dcms-input-text dcms-focus dcms-hover user-manager-input-text', 'disabled']); // @todo ! *ACTIVE* ! Need to figure out how to let Users that use this User know when the Users's name is changed and update accordingly.*EOL* NOTE:*EOL*- This MUST not create new dependency between the relevant objects*EOL*- This MUST not effect the app's current functional state as it is at present, i.e. MUST not introduce new bugs
                    echo $userNameInput->getHtml();
                    ?>
                </div>
            </td>
            <td>
                <div class="user-manager-table-cell-content-container">
                    <?php
                    $assignedRoleNames = array();
                    $assignedRoleIncrementer = 0;
                    foreach ($user->getRoles() as $role) {
                        array_push($assignedRoleNames, $role->getRoleName());
                        $roleCheckbox = new \DarlingCms\classes\html\form\Checkbox($role->getRoleName() . '-checkbox', $role->getRoleName(), ['id' => $userElementIdPrefix . 'AssignedRoleCheckbox' . strval($assignedRoleIncrementer), 'checked', 'class' => 'dcms-input-checkbox dcms-focus dcms-hover user-manager-input-checkbox']);
                        echo '<div title="Un-check to un-assign..." class="user-manager-assigned-role-checkbox">' . $roleCheckbox->getHtml() . $role->getRoleName() . '</div>';
                        $assignedRoleIncrementer++;
                    }
                    ?>
                </div>
            </td>
            <td>
                <div class="user-manager-table-cell-content-container">
                    <?php
                    $availableRoleNames = array();
                    $availableRoleIncrementer = 0;
                    foreach ($roleCrud->readAll() as $role) {
                        if (in_array($role->getRoleName(), $assignedRoleNames, true) === true) {
                            continue;
                        }
                        array_push($availableRoleNames, $role->getRoleName());
                        $roleCheckbox = new \DarlingCms\classes\html\form\Checkbox($role->getRoleName() . '-checkbox', $role->getRoleName(), ['id' => $userElementIdPrefix . 'AvailableRoleCheckbox' . strval($availableRoleIncrementer), 'class' => 'dcms-input-checkbox dcms-focus dcms-hover user-manager-input-checkbox']);
                        echo '<div title="Check to assign..." class="user-manager-available-role-checkbox">' . $roleCheckbox->getHtml() . $role->getRoleName() . '</div>';
                        $availableRoleIncrementer++;
                    }
                    ?>
                </div>
            </td>
            <td>
                <div class="user-manager-table-cell-content-container">
                    <div class="user-manager-meta-data-container">
                        <?php
                        $publicMetaDataIncrementer = 0;
                        if (empty($user->getPublicMeta()) === false) {
                            ?>
                            <p class="user-manager-small-text user-manager-hint">Use the fields below to edit the user's
                                meta data.</p>
                            <?php
                            foreach ($user->getPublicMeta() as $publicMetaKey => $publicMetaValue) {
                                $publicMetaKeyText = new \DarlingCms\classes\html\form\Text($publicMetaKey . '-public-meta-key', $publicMetaKey, ['id' => $userElementIdPrefix . 'PublicMetaKeyTextInput' . strval($publicMetaDataIncrementer), 'class' => 'dcms-input-text dcms-focus dcms-hover user-manager-input-text']);
                                $publicMetaValueText = new \DarlingCms\classes\html\form\Text($publicMetaKey . '-public-meta-value', $publicMetaValue, ['id' => $userElementIdPrefix . 'PublicMetaValueTextInput' . strval($publicMetaDataIncrementer), 'class' => 'dcms-input-text dcms-focus dcms-hover user-manager-input-text']);
                                ?>
                                <div title="Meta Data Name | Hint: To delete meta data, simply clear the name and value fields of the meta data that should be deleted on update."
                                     class="user-manager-meta-key-text-input">
                                    <?php echo $publicMetaKeyText->getHtml(); ?>
                                </div>
                                <div title="Meta Data Value | Hint: To delete meta data, simply clear the name and value fields of the meta data that should be deleted on update."
                                     class="user-manager-meta-value-text-input">
                                    <?php echo $publicMetaValueText->getHtml(); ?>
                                </div>
                                <div class="user-manager-meta-separator"></div>
                                <?php
                                $publicMetaDataIncrementer++;
                            }
                        }
                        $newPublicMetaKeyText = new \DarlingCms\classes\html\form\Text('new-public-meta-key', '', ['id' => $userElementIdPrefix . 'PublicMetaKeyTextInput' . strval($publicMetaDataIncrementer), 'class' => 'dcms-input-text dcms-focus dcms-hover user-manager-input-text', 'placeholder' => 'Enter New Name...']);
                        $newPublicMetaValueText = new \DarlingCms\classes\html\form\Text('new-public-meta-value', '', ['id' => $userElementIdPrefix . 'PublicMetaValueTextInput' . strval($publicMetaDataIncrementer), 'class' => 'dcms-input-text dcms-focus dcms-hover user-manager-input-text', 'placeholder' => 'Enter New Value...']);
                        ?>
                        <p class="user-manager-small-text user-manager-hint">Hint: Use the empty fields below to add new
                            meta data.</p>
                        <div title="Enter a name for the new meta data. Hint: New meta data will only be assigned to the user if both the name and value fields are set."
                             class="user-manager-meta-key-text-input">
                            <?php echo $newPublicMetaKeyText->getHtml(); ?>
                        </div>
                        <div title="Enter a value for the new meta data. Hint: New meta data will only be assigned to the user if both the name and value fields are set."
                             class="user-manager-meta-value-text-input">
                            <?php echo $newPublicMetaValueText->getHtml(); ?>
                        </div>
                        <div class="user-manager-meta-separator"></div>
                    </div>
                </div>
            </td>
            <td>
                <div class="user-manager-table-cell-content-container">
                    <div class="user-manager-meta-data-container">
                        <?php
                        $privateMetaDataIncrementer = 0;
                        if (empty($user->getPrivateMeta()) === false) {
                            ?>
                            <p class="user-manager-small-text user-manager-hint">Use the fields below to edit the user's
                                meta data.</p>
                            <?php
                            foreach ($user->getPrivateMeta() as $privateMetaKey => $privateMetaValue) {
                                $privateMetaKeyText = new \DarlingCms\classes\html\form\Text($privateMetaKey . '-private-meta-key', $privateMetaKey, ['id' => $userElementIdPrefix . 'PrivateMetaKeyTextInput' . strval($privateMetaDataIncrementer), 'class' => 'dcms-input-text dcms-focus dcms-hover user-manager-input-text']);
                                $privateMetaValueText = new \DarlingCms\classes\html\form\Text($privateMetaKey . '-private-meta-value', $privateMetaValue, ['id' => $userElementIdPrefix . 'PrivateMetaValueTextInput' . strval($privateMetaDataIncrementer), 'class' => 'dcms-input-text dcms-focus dcms-hover user-manager-input-text']);
                                ?>
                                <div title="Meta Data Name | Hint: To delete meta data, simply clear the name and value fields of the meta data that should be deleted on update."
                                     class="user-manager-meta-key-text-input">
                                    <?php echo $privateMetaKeyText->getHtml(); ?>
                                </div>
                                <div title="Meta Data Value | Hint: To delete meta data, simply clear the name and value fields of the meta data that should be deleted on update."
                                     class="user-manager-meta-value-text-input">
                                    <?php echo $privateMetaValueText->getHtml(); ?>
                                </div>
                                <div class="user-manager-meta-separator"></div>
                                <?php
                                $privateMetaDataIncrementer++;
                            }
                        }
                        $newPrivateMetaKeyText = new \DarlingCms\classes\html\form\Text('new-private-meta-key', '', ['id' => $userElementIdPrefix . 'PrivateMetaKeyTextInput' . strval($privateMetaDataIncrementer), 'class' => 'dcms-input-text dcms-focus dcms-hover user-manager-input-text', 'placeholder' => 'Enter New Name...']);
                        $newPrivateMetaValueText = new \DarlingCms\classes\html\form\Text('new-private-meta-value', '', ['id' => $userElementIdPrefix . 'PrivateMetaValueTextInput' . strval($privateMetaDataIncrementer), 'class' => 'dcms-input-text dcms-focus dcms-hover user-manager-input-text', 'placeholder' => 'Enter New Value...']);
                        ?>
                        <p class="user-manager-small-text user-manager-hint">Hint: Use the empty fields below to add new
                            meta data.</p>
                        <div title="Enter a name for the new meta data. Hint: New meta data will only be assigned to the user if both the name and value fields are set."
                             class="user-manager-meta-key-text-input">
                            <?php echo $newPrivateMetaKeyText->getHtml(); ?>
                        </div>
                        <div title="Enter a value for the new meta data. Hint: New meta data will only be assigned to the user if both the name and value fields are set."
                             class="user-manager-meta-value-text-input">
                            <?php echo $newPrivateMetaValueText->getHtml(); ?>
                        </div>
                        <div class="user-manager-meta-separator"></div>
                    </div>
                </div>
            </td>
            <td class="user-manager-table-save-changes">
                <div class="user-manager-table-cell-content-container">
                    <?php
                    $assignedRoleParamStr = '';
                    for ($i = 0; $i <= (count($assignedRoleNames) - 1); $i++) {
                        $assignedRoleTargetId = $userElementIdPrefix . 'AssignedRoleCheckbox' . $i;
                        $assignedRoleParamStr .= '&' . 'assignedRoleNames[]=\'+getElementValue(\'' . $assignedRoleTargetId . '\')+\'' . '&' . 'assignedRoleStates[]=\'+checkboxIsChecked(\'' . $assignedRoleTargetId . '\')+\'';
                    }
                    $availableRoleParamStr = '';
                    for ($i = 0; $i <= (count($availableRoleNames) - 1); $i++) {
                        $availableRoleTargetId = $userElementIdPrefix . 'AvailableRoleCheckbox' . $i;
                        $availableRoleParamStr .= '&' . 'availableRoleNames[]=\'+getElementValue(\'' . $availableRoleTargetId . '\')+\'' . '&' . 'availableRoleStates[]=\'+checkboxIsChecked(\'' . $availableRoleTargetId . '\')+\'';
                    }
                    $publicMetaDataKeyParamStr = '';
                    $publicMetaDataValueParamStr = '';
                    for ($i = 0; $i <= ((count($user->getPublicMeta()) + 1) - 1); $i++) {
                        $publicMetaDataTargetKeyId = $userElementIdPrefix . 'PublicMetaKeyTextInput' . $i;
                        $publicMetaDataTargetValueId = $userElementIdPrefix . 'PublicMetaValueTextInput' . $i;
                        $publicMetaDataKeyParamStr .= '&' . 'publicMetaDataKeys[]=\'+getElementValue(\'' . $publicMetaDataTargetKeyId . '\')+\'';
                        $publicMetaDataValueParamStr .= '&' . 'publicMetaDataValues[]=\'+getElementValue(\'' . $publicMetaDataTargetValueId . '\')+\'';
                    }
                    $privateMetaDataKeyParamStr = '';
                    $privateMetaDataValueParamStr = '';
                    for ($i = 0; $i <= ((count($user->getPrivateMeta()) + 1) - 1); $i++) {
                        $privateMetaDataTargetKeyId = $userElementIdPrefix . 'PrivateMetaKeyTextInput' . $i;
                        $privateMetaDataTargetValueId = $userElementIdPrefix . 'PrivateMetaValueTextInput' . $i;
                        $privateMetaDataKeyParamStr .= '&' . 'privateMetaDataKeys[]=\'+getElementValue(\'' . $privateMetaDataTargetKeyId . '\')+\'';
                        $privateMetaDataValueParamStr .= '&' . 'privateMetaDataValues[]=\'+getElementValue(\'' . $privateMetaDataTargetValueId . '\')+\'';
                    }
                    $updateAjaxReq = \DarlingCms\abstractions\userInterface\AjaxUi::generateAjaxRequest([
                        'issuingApp' => 'UserManager',
                        'handlerName' => 'updateUserHandler',
                        'outputElementId' => 'UserManagerView',
                        'requestType' => 'POST',
                        'contentType' => '',
                        'additionalParams' => 'originalUserName=\'+this.dataset.userName+\'' . '&' . 'userName=\'+getElementValue(\'' . $userNameElementId . '\')+\'' . $assignedRoleParamStr . $availableRoleParamStr . $publicMetaDataKeyParamStr . $publicMetaDataValueParamStr . $privateMetaDataKeyParamStr . $privateMetaDataValueParamStr,
                        'ajaxDirName' => 'handlers',
                        'callFunction' => '',
                        'callContext' => '',
                        'callArgs' => ''
                    ]);
                    $updateButton = new \DarlingCms\classes\html\HtmlTag('button', ['onclick' => 'confirm(\'Are you sure you want to update user ' . $user->getUserName() . '?\') === true ? ' . $updateAjaxReq . ' : console.log(\'Canceled request to update user ' . $user->getUserName() . '.\')', 'data-user-name' => $user->getUserName(), 'class' => 'dcms-button user-manager-update-user-button'], 'Update User');
                    echo $updateButton->getHtml();
                    ?>
                </div>
            </td>
            <td class="user-manager-table-delete-user">
                <div class="user-manager-table-cell-content-container">
                    <?php
                    $deleteAjaxReq = \DarlingCms\abstractions\userInterface\AjaxUi::generateAjaxRequest([
                        'issuingApp' => 'UserManager',
                        'handlerName' => 'deleteUserHandler',
                        'outputElementId' => 'UserManagerView',
                        'requestType' => 'POST',
                        'contentType' => '',
                        'additionalParams' => 'userName=\'+getElementValue(\'' . $userNameElementId . '\')+\'',
                        'ajaxDirName' => 'handlers',
                        'callFunction' => '',
                        'callContext' => '',
                        'callArgs' => ''
                    ]);
                    $deleteButton = new \DarlingCms\classes\html\HtmlTag('button', ['onclick' => 'confirm(\'Are you sure you want to delete user ' . $user->getUserName() . '?\') === true ? ' . $deleteAjaxReq . ' : console.log(\'Canceled request to delete the ' . $user->getUserName() . ' user.\')', 'data-user-name' => $user->getUserName(), 'class' => 'dcms-button user-manager-delete-user-button'], 'Delete User');
                    echo $deleteButton->getHtml();
                    ?>
                </div>
            </td>
        </tr>
        <?php
    }
    ?>
</table>
