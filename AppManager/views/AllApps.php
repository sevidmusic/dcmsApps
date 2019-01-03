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
        $appName = $appConfigObject->getName();
        ?>
        <div class="am-appInfo-container">
            <h1><?php echo $appName; ?></h1>
            <?php echo $userInterface->getAppOnOffSelect($appName); ?>
            <?php echo $userInterface->getAbout($appName); ?>
            <?php if ($userInterface->displayAdvancedInfo() === true) { ?>
                <div class="am-appInfo-sub-container">
                    <h4>Dev Info</h4>
                    <p>Namespace: <span><?php echo $appInfo->getNamespace($appName); ?></span></p>
                    <p>Path: <span><?php echo $appInfo->getPath($appName); ?></span></p>
                </div>
                <div class="am-appInfo-sub-container">
                    <h4>Themes</h4>
                    <?php echo $userInterface->getThemeList($appName); ?>
                </div>
                <div class="am-appInfo-sub-container">
                    <h4>Javascript Libraries</h4>
                    <?php echo $userInterface->getJsLibraryList($appName); ?>
                </div>
            <?php } // end if ($userInterface->displayAdvancedInfo() === true) {...} ?>
        </div>
        <?php
    } // end foreach ($appInfo->getAppConfigObjects() as $appConfigObject) {...}
}
