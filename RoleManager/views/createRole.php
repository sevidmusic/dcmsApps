<?php
if (filter_input(INPUT_GET, 'ajaxRequest') === 'true') {
    require str_replace('/apps/RoleManager/views', '/vendor/autoload.php', __DIR__);
}
?>
    <h1>Create a new role</h1>
<?php
$createRoleForm = new \DarlingCms\classes\html\form\Form(
    'post',
    [
        'id' => 'createRoleForm',
        'class' => 'role-manager-form',
        'onsubmit' => 'return ' . \DarlingCms\abstractions\userInterface\AjaxUi::generateAjaxRequest([
                'issuingApp' => 'RoleManager',
                'handlerName' => 'createRoleHandler',
                'outputElementId' => 'RoleManagerView',
                'requestType' => 'POST',
                'contentType' => '',
                'additionalParams' => 'roleName=\'+getElementValue(\'roleNameFormElement\')+\'',
                'ajaxDirName' => 'handlers',
                'callFunction' => '',
                'callContext' => '',
                'callArgs' => ''
            ])
    ]
);
$createRoleForm->addHtml(new \DarlingCms\classes\html\HtmlTag('label', ['for' => 'roleName'], 'Role Name'));
$createRoleForm->addFormElement(new \DarlingCms\classes\html\form\Text('roleName', '', ['id' => 'roleNameFormElement', 'class' => 'dcms-input-text dcms-focus dcms-hover']));
$createRoleForm->addFormElement(new \DarlingCms\classes\html\form\Submit('saveNewRole', 'Save New Role', ['id' => 'saveNewRoleFormElement', 'class' => 'dcms-button dcms-focus dcms-hover']));
echo $createRoleForm->getHtml();
