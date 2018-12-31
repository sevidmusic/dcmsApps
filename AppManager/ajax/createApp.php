<?php
if (filter_input(INPUT_POST, 'ajaxRequest') === 'true') {
    require '../../../vendor/autoload.php';
}
$appManager = new \Apps\AppManager\classes\AppManager(new \DarlingCms\classes\FileSystem\ZipCrud());
if (empty(filter_input(INPUT_POST, 'appName')) === false) {
    $appName = filter_input(INPUT_POST, 'appName');
    $appManager->createApp($appName);
    echo "Created new app {$appName}";
}
