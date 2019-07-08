<?php use DarlingCms\classes\staticClasses\core\CoreValues; ?>
<div id="ARFrame" class="dcms-admin-panel dcms-admin-panel-pos3 dcms-make-draggable">
    <div id="ARFrameHandle" class="dcms-drag-handle">Click Here To Drag</div>
    <?php
    /**
     * @devNote: An iframe is used because it makes it easier to contain the AR Scene.
     *           Directly embedding the AR Scene into a web page often leads to the AR Scene
     *           completely taking over the page, this is obviously undesirable.
     *
     *           This solution was inspired by an answer on stack overflow by Piotr Adam Milewski:
     *
     * @see https://stackoverflow.com/questions/53725139/how-to-hide-the-overlays-in-a-frame/53726565#53726565
     */
    ?>
    <iframe src="<?php echo CoreValues::getSiteRootUrl() . "apps/ARFrame/ArScene.php"; ?>"
            style="width: 100%; height: 100%;"></iframe>
</div>
