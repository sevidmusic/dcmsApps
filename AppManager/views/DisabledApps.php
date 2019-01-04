<?php
if (filter_input(INPUT_GET, 'ajaxRequest') === 'true') {
    require realpath('../../../vendor/autoload.php');
}
$enabledAppInfo = new \Apps\AppManager\classes\AppInfo();
$disabledAppInfo = new \Apps\AppManager\classes\DisabledAppInfo(new \DarlingCms\classes\FileSystem\ZipCrud());
$appInfoObjects = array($disabledAppInfo);
foreach ($appInfoObjects as $appInfo) {
    $userInterface = new \Apps\AppManager\classes\AppManagerUI($appInfo);
    foreach ($appInfo->getAppConfigObjects() as $appConfigObject) {
        $appName = $appConfigObject->getName();
        ?>
        <div class="am-appInfo-container">
            <div class="dcms-float-right am-app-logo-container">
                <?php echo $userInterface->getAppLogoImg($appName); ?>
            </div>
            <h1 class="dcms-positive-text">
                <?php echo $appName; ?>
            </h1>
            <?php echo $userInterface->getAppOnOffSelect($appName); ?>
            <div class="dcms-float-left am-appInfo-sub-container">
                <h4>About</h4>
                <div class="am-app-readme-container">
                    <?php echo $appInfo->getReadme($appName); ?>
                </div>
            </div>
            <?php
            if ($userInterface->displayAdvancedInfo() === true) {
                ?>
                <div class="dcms-float-left am-appInfo-sub-container am-advanced-info-container">
                    <h4>Dev Info</h4>
                    <p class="dcms-descriptive-text">Namespace: <span
                                class="dcms-descriptive-text-highlight">
                        <?php echo $appInfo->getNamespace($appName); ?>
                    </span>
                    </p>
                    <p class="dcms-descriptive-text">Path: <span
                                class="dcms-descriptive-text-highlight">
                        <?php echo $appInfo->getPath($appName); ?>
                    </span>
                    </p>
                </div>
                <div class="dcms-float-left am-appInfo-sub-container">
                    <h4>Themes</h4>
                    <?php echo $userInterface->getThemeList($appName); ?>
                </div>
                <div class="dcms-float-left am-appInfo-sub-container">
                    <h4>Javascript Libraries</h4>
                    <?php echo $userInterface->getJsLibraryList($appName); ?>
                </div>
            <?php } // end if ($userInterface->displayAdvancedInfo() === true) {...} ?>
            <div class="dcms-clear-float"></div>
        </div>
        <?php
    } // end foreach ($appInfo->getAppConfigObjects() as $appConfigObject) {...}
}
