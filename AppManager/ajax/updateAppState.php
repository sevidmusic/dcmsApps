<?php
require_once '../classes/AppManager.php';
$appManager = new \Apps\AppManager\classes\AppManager();
if (filter_input(INPUT_GET, 'updateAppState') === 'On' && $appManager->enableApp(filter_input(INPUT_GET, 'appName')) === true) {
    $status = true;
    echo "<p class='dcms-positive-text'>Successfully turned on the " . filter_input(INPUT_GET, 'appName') . ' app.</p>';
}
if (filter_input(INPUT_GET, 'updateAppState') === 'Off' && $appManager->disableApp(filter_input(INPUT_GET, 'appName')) === true) {
    $status = true;
    echo "<p class='dcms-positive-text'>Successfully turned off the " . filter_input(INPUT_GET, 'appName') . ' app.</p>';
}
if (isset($status) === false) {
    echo "<p class='dcms-negative-text'>Failed turned " . filter_input(INPUT_GET, 'updateAppState') . " the " . filter_input(INPUT_GET, 'appName') . ' app.</p>';
}
