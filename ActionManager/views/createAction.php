<?php
if (filter_input(INPUT_GET, 'ajaxRequest') === 'true') {
    require str_replace('/apps/ActionManager/views', '/vendor/autoload.php', __DIR__);
}
?>
    <h1>Create a new action</h1>
<?php
$createActioForm = new \DarlingCms\classes\html\form\Form(
    'post',
    [
        'id' => 'createActionForm',
        'class' => 'action-manager-form'
    ]
);
$createActioForm->addHtml(new \DarlingCms\classes\html\HtmlTag('label', ['for' => 'actionName'], 'Action Name'));
$createActioForm->addFormElement(new \DarlingCms\classes\html\form\Text('actionName', '', ['id' => 'actionNameFormElement', 'class' => 'dcms-input-text action-manager-input-text']));
$createActioForm->addHtml(new \DarlingCms\classes\html\HtmlTag('label', ['for' => 'actionDescription'], 'Action Description'));
$createActioForm->addFormElement(new \DarlingCms\classes\html\form\TextArea('actionDescription', ['id' => 'actionDescriptionFormElement', 'class' => 'dcms-input-textarea action-manager-input-textarea']));
$createActioForm->addFormElement(new \DarlingCms\classes\html\form\Submit('saveNewAction', 'Save New Action', ['id' => 'saveNewActionFormElement', 'class' => 'dcms-input-submit action-manager-input-submit']));
echo $createActioForm->getHtml();
