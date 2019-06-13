<?php

use DarlingCms\classes\FileSystem\DirectoryCrud;
use DarlingCms\classes\staticClasses\core\CoreValues;
use DarlingCms\classes\staticClasses\utility\ArrayUtility;

//$DocComments = new ReflectionClass('\DarlingCms\interfaces');
//$IAccessControllerDocComments = new ReflectionClass('\DarlingCms\interfaces\accessControl\IAccessController');

require_once 'functions.php';
echo '<div id="DarlingCmsDocumentation" class="dcms-admin-panel dcms-admin-panel-pos1 dcms-make-draggable todo-container">';
echo '<div id="DarlingCmsDocumentationHandle" draggable="true" class="dcms-drag-handle">Click here to drag...</div>';
$interfaceDir = new DirectoryCrud(CoreValues::getSiteRootDirPath() . '/core');
$docComments = getDocComments(getReflections(convertPathsToNamespaces(getSubPhpFilePaths($interfaceDir))));
foreach ($docComments as $name => $docComment) {
    if (empty($docComment) === true) {
        error_log('Darling Cms Documentation Notice: There are no doc comments defined for ' . $name);
        continue;
    }
    $linkName = str_replace(['\\', 'DarlingCms/'], ['/', ''], $name) . '.php';
    $link = 'https://github.com/sevidmusic/DarlingCms/tree/darlingCms_0.1_dev/core/' . $linkName;
    echo '<div class="dcms-doc-comment-container"><h1>' . $name . '</h1><p class="dcms-doc-comment">' . str_replace('@', '<br><br>@', $docComment) . '</p><p>View on GitHub: <a style="font-size:.9em;" href="' . $link . '" target="_blank">' . $link . '</a></p></div>';
}
echo '</div>';
