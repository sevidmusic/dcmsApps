<?php
$appInfo = new \Apps\AppManager\classes\AppInfo();
$userInterface = new \Apps\AppManager\classes\AppManagerUI($appInfo);
foreach ($appInfo->getAppConfigObjects() as $appConfigObject) {
    $appName = $appConfigObject->getName();
    ?>
    <div class="dcms-admin-container dcms-full-width">
        <div class="am-app-logo-container dcms-float-right">
            <?php echo $userInterface->getAppLogoImg($appName); ?>
        </div>
        <h1 class="dcms-positive-text">
            <?php echo $appName; ?>
        </h1>
        <?php echo $userInterface->getAppOnOffSelect($appName); ?>
        <div class="dcms-sub-container dcms-float-left dcms-container-border-center dcms-full-width">
            <h4>About</h4>
            <div class="am-app-readme-container">
                <?php echo $appInfo->getReadme($appName); ?>
            </div>
        </div>
        <?php
        if ($userInterface->displayAdvancedInfo() === true) {
            ?>
            <div class="dcms-sub-container dcms-float-left dcms-container-border-right dcms-third-width dcms-short-container am-advanced-info-container">
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
            <div class="dcms-sub-container dcms-float-left dcms-container-border-middle dcms-third-width dcms-short-container">
                <h4>Themes</h4>
                <?php echo $userInterface->getThemeList($appName); ?>
            </div>
            <div class="dcms-sub-container dcms-float-left dcms-container-border-left dcms-third-width dcms-short-container">
                <h4>Javascript Libraries</h4>
                <?php echo $userInterface->getJsLibraryList($appName); ?>
            </div>
        <?php } // end if ($userInterface->displayAdvancedInfo() === true) {...} ?>
        <div class="dcms-clear-float"></div>
    </div>
<?php } // end foreach ($appInfo->getAppConfigObjects() as $appConfigObject) {...} ?>

