<div id="AppManagerContainer" class="makeDraggable dcms-admin-panel">
    <div id="AppManagerContainerHandle" draggable="true" class="dragHandle">Click here to move...</div>
    <?php
    $userInterface = new \Apps\AppManager\classes\AppManagerUI(new \Apps\AppManager\classes\AppInfo());
    echo $userInterface->getViewLinks();
    echo $userInterface->getToolbar();
    ?>
    <div id="AppManagerCurrentView">
        <?php echo $userInterface->getUserInterface(); ?>
    </div>
</div>
