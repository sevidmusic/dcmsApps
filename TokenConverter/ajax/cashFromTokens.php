<?php

if (filter_input(INPUT_GET, 'ajaxRequest') === 'true') {
    require str_replace('/apps/TokenConverter/ajax', '/vendor/autoload.php', __DIR__);
}

use DarlingCms\classes\html\HtmlBlock;
use DarlingCms\classes\html\HtmlContainer;
use DarlingCms\classes\html\HtmlTag;

if (empty(filter_input(INPUT_GET, 'tokens')) === false) {
    $msg = new HtmlContainer(new HtmlBlock(), 'div', ['class' => 'dcms-form-msg']);
    $earned = bcmul(filter_input(INPUT_GET, 'tokens'), '0.5', 2);
    $msg->addHtml(new HtmlTag('p', ['dcms-positive-text'], 'You have earned $' . ($earned < 1 ? '0' . $earned : $earned)));
    echo $msg->getHtml();
}
