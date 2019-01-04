<?php
if (filter_input(INPUT_GET, 'ajaxRequest') === 'true') {
    require realpath('../../../vendor/autoload.php');
}
echo '<h1>Create New App</h1>';
echo '<p>Create boilerplate code for a new Darling Cms App</p>';
$createAppForm = new \DarlingCms\classes\html\form\Form('post', ['onsubmit' => "return AjaxRouterRequest('AppManager','createApp','am-message','POST',undefined,'appName='+this.children[0].value, 'ajax','AMShowMessages')"]);
$createAppForm->addFormElement(new \DarlingCms\classes\html\form\Text('appName', '', ['class' => 'dcms-input-text']));
$createAppForm->addFormElement(new \DarlingCms\classes\html\form\Submit('createApp', 'Create New App',['class' => 'dcms-button']));
echo $createAppForm->getHtml();
