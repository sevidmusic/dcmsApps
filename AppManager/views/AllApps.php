<?php
if (filter_input(INPUT_GET, 'ajaxRequest') === 'true') {
    require realpath('../../../vendor/autoload.php');
}
$enabledAppInfo = new \Apps\AppManager\classes\AppInfo();
$disabledAppInfo = new \Apps\AppManager\classes\DisabledAppInfo(new \DarlingCms\classes\FileSystem\ZipCrud());
$appInfoObjects = array($enabledAppInfo, $disabledAppInfo);
foreach ($appInfoObjects as $appInfo) {
    $userInterface = new \Apps\AppManager\classes\AppManagerUI($appInfo);
    foreach ($appInfo->getAppConfigObjects() as $appConfigObject) {
        include 'subViews/appInfoContainer.php';
    } // end foreach ($appInfo->getAppConfigObjects() as $appConfigObject) {...}
}
