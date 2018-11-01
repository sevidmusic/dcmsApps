<?php
$appInfo = new \Apps\AppManager\classes\AppInfo(\Apps\AppManager\classes\AppInfo::STARTUP_DEFAULT, 'AppManager');
$userInterface = new \Apps\AppManager\classes\AppManagerUI($appInfo);
foreach ($appInfo->getAppConfigObjects() as $appConfigObject) { ?>
    <div class="dcms-admin-container dcms-full-width">
        <div class="am-app-logo-container dcms-float-right">
            <?php echo $userInterface->getAppLogoImg($appConfigObject->getName()); ?>
        </div>
        <h1 class="dcms-positive-text">
            <?php echo $appConfigObject->getName(); ?>
        </h1>
        <?php echo $userInterface->getAppOnOffSelect($appConfigObject->getName()); ?>
        <?php echo $userInterface->getAbout($appConfigObject->getName()); ?>
        <?php
        if ($userInterface->displayAdvancedInfo() === true) {
            ?>
            <div class="dcms-sub-container dcms-float-left dcms-container-border-middle dcms-quarter-width dcms-short-container am-advanced-info-container">
                <h4>Dev Info</h4>
                <p class="dcms-descriptive-text">Namespace: <span
                            class="dcms-descriptive-text-highlight">
                        <?php echo $appInfo->getNamespace($appConfigObject->getName()); ?>
                    </span>
                </p>
                <p class="dcms-descriptive-text">Path: <span
                            class="dcms-descriptive-text-highlight">
                        <?php echo $appInfo->getPath($appConfigObject->getName()); ?>
                    </span>
                </p>
            </div>
            <div class="dcms-sub-container dcms-float-left dcms-container-border-middle dcms-quarter-width dcms-short-container">
                <h4>Themes</h4>
                <?php echo $userInterface->getThemeList($appConfigObject->getName()); ?>
            </div>
            <div class="dcms-sub-container dcms-float-left dcms-container-border-left dcms-quarter-width dcms-short-container">
                <h4>Javascript Libraries</h4>
                <?php echo $userInterface->getJsLibraryList($appConfigObject->getName()); ?>
            </div>
        <?php } // end if ($userInterface->displayAdvancedInfo() === true) {...} ?>
        <div class="dcms-clear-float"></div>
    </div>
<?php } // end foreach ($appInfo->getAppConfigObjects() as $appConfigObject) {...} ?>

