<?php
if (filter_input(INPUT_GET, 'ajaxRequest') === 'true') {
    require realpath('../../../vendor/autoload.php');
}
echo '<h1>Create New App</h1>';
echo '<p>Create boilerplate code for a new Darling Cms App</p>';
$ajaxRouterRequest = \DarlingCms\abstractions\userInterface\AjaxUI::generateAjaxRequest(
    [
        'issuingApp' => 'AppManager',
        'handlerName' => 'createApp',
        'outputElementId' => 'am-message',
        'requestType' => 'POST',
        'additionalParams' => 'appName=\'+getElementValue(\'newAppName\')+\'',
        'callFunction' => 'AMShowMessages',
    ]
);
$createAppForm = new \DarlingCms\classes\html\form\Form('post', ['onsubmit' => 'return ' . $ajaxRouterRequest]);
$createAppForm->addFormElement(new \DarlingCms\classes\html\form\Text('appName', '', ['id' => 'newAppName', 'class' => 'dcms-input-text']));
$createAppForm->addFormElement(new \DarlingCms\classes\html\form\Submit('createApp', 'Create New App', ['class' => 'dcms-button']));
echo $createAppForm->getHtml();
