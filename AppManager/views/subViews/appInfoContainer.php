<?php
/**
 * @devNote This file is intended to be included from one of the App Manager's main views and it's
 * functionality cannot be guaranteed outside of this context.
 */
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
