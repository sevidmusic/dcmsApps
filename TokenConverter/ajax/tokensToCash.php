<?php
if (filter_input(INPUT_GET, 'ajaxRequest') === 'true') {
    require str_replace('/apps/TokenConverter/ajax', '/vendor/autoload.php', __DIR__);
}

use DarlingCms\classes\html\form\Form;
use DarlingCms\classes\html\form\Text;
use DarlingCms\abstractions\userInterface\AjaxUi;
use DarlingCms\classes\html\HtmlBlock;
use DarlingCms\classes\html\HtmlContainer;
use DarlingCms\classes\html\HtmlTag;

$ajax = AjaxUi::generateAjaxRequest([
    'issuingApp' => 'TokenConverter',
    'handlerName' => 'cashFromTokens',
    'outputElementId' => 'TokenConversionView',
    'requestType' => 'GET',
    'contentType' => 'application/x-www-form-urlencoded',
    'additionalParams' => 'tokens=\'+getElementValue(\'tokensInput\')+\'',
    'ajaxDirName' => 'utility',
    'callFunction' => '',
    'callContext' => '',
    'callArgs' => ''
]);

$form = new Form('get', ['class' => 'dcms-form'], new Text('tokens', (!empty(filter_input(INPUT_GET, 'tokens')) === true ? filter_input(INPUT_GET, 'tokens') : '0'), ['id' => 'tokensInput', 'class' => 'dcms-input-text', 'oninput' => 'return ' . $ajax], true));
echo $form->getHtml();

$button = new HtmlTag('button', ['class' => 'dcms-button', 'onclick' => 'return ' . $ajax], 'Convert To Cash');
echo $button->getHtml();

echo '<div id="TokenConversionView">';
// Still output conversion for non-ajax requests
if (filter_input(INPUT_GET, 'ajaxRequest') !== 'true' && !empty(filter_input(INPUT_GET, 'tokens')) === true) {
    $msg = new HtmlContainer(new HtmlBlock(), 'div', ['class' => 'dcms-form-msg']);
    $earned = bcmul(filter_input(INPUT_GET, 'tokens'), '0.05', 2);
    $msg->addHtml(new HtmlTag('p', ['dcms-positive-text'], 'You have earned $' . ($earned < 1 ? '0' . $earned : $earned)));
    echo $msg->getHtml();
}
echo '</div>';


