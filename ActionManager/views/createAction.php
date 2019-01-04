<?php
if (filter_input(INPUT_GET, 'ajaxRequest') === 'true') {
    require str_replace('/apps/ActionManager/views', '/vendor/autoload.php', __DIR__);
}
?>
    <h1>Create a new action</h1>
<?php
$createActionForm = new \DarlingCms\classes\html\form\Form(
    'post',
    [
        'id' => 'createActionForm',
        'class' => 'action-manager-form',
        'onsubmit' => 'return ' . \DarlingCms\abstractions\userInterface\AjaxUi::generateAjaxRequest([
                'issuingApp' => 'ActionManager',
                'handlerName' => 'createActionHandler',
                'outputElementId' => 'ActionManagerView',
                'requestType' => 'POST',
                'contentType' => '',
                'additionalParams' => 'actionName=\'+this.children[1].value+\'&' . 'actionDescription=\'+this.children[3].value+\'',
                'ajaxDirName' => 'handlers',
                'callFunction' => '',
                'callContext' => '',
                'callArgs' => ''
            ])
    ]
);
$createActionForm->addHtml(new \DarlingCms\classes\html\HtmlTag('label', ['for' => 'actionName'], 'Action Name'));
$createActionForm->addFormElement(new \DarlingCms\classes\html\form\Text('actionName', '', ['id' => 'actionNameFormElement', 'class' => 'dcms-input-text dcms-focus dcms-hover']));
$createActionForm->addHtml(new \DarlingCms\classes\html\HtmlTag('label', ['for' => 'actionDescription'], 'Action Description'));
$createActionForm->addFormElement(new \DarlingCms\classes\html\form\TextArea('actionDescription', ['id' => 'actionDescriptionFormElement', 'class' => 'dcms-input-textarea dcms-focus dcms-hover']));
$createActionForm->addFormElement(new \DarlingCms\classes\html\form\Submit('saveNewAction', 'Save New Action', ['id' => 'saveNewActionFormElement', 'class' => 'dcms-button dcms-focus dcms-hover']));
echo $createActionForm->getHtml();
