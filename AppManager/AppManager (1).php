<div id="AppManager" class="dcms-admin-panel dcms-admin-panel-pos2 dcms-make-draggable">
    <div id="AppManagerHandle" draggable="true" class="dcms-drag-handle">Click here to move...</div>
    <?php
    $userInterface = new \Apps\AppManager\classes\AppManagerUI(new \Apps\AppManager\classes\AppInfo());
    echo $userInterface->getToolbar();
    echo $userInterface->getViewLinks();
    ?>
    <div id="AppManagerCurrentView">
        <?php echo $userInterface->getUserInterface(); ?>
    </div>
</div>
