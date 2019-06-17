<?php

use DarlingCms\classes\FileSystem\DirectoryCrud;
use DarlingCms\classes\staticClasses\core\CoreValues;

require_once 'functions.php';
?>
<div id="DarlingCmsDocumentation" class="dcms-admin-panel dcms-admin-panel-pos1 dcms-make-draggable todo-container">
    <div id="DarlingCmsDocumentationHandle" draggable="true" class="dcms-drag-handle">Click here to drag...</div>
    <div>
        <a href="https://github.com/sevidmusic/DarlingCms" target="_blank">
            View the Darling Cms on Github
        </a>
    </div>
    <?php
    $interfaceDir = new DirectoryCrud(CoreValues::getSiteRootDirPath() . '/core');
    $docComments = getDocComments(getReflections(convertPathsToNamespaces(getSubPhpFilePaths($interfaceDir))));
    $docCommentHtml = '';
    $docCommentMenu = '';
    foreach ($docComments as $name => $docComment) {
        $htmlId = sprintf('%s', str_replace(['\\'], [''], $name));
        $docCommentMenu .= '<a  href="#' . $htmlId . '">' . $name . '</a><br/>';
        if (empty($docComment) === true) {
            error_log('Darling Cms Documentation Notice: There are no doc comments defined for ' . $name);
            continue;
        }
        $link = sprintf("https://github.com/sevidmusic/DarlingCms/tree/darlingCms_0.1_dev/core/%s", str_replace(['\\', 'DarlingCms/'], ['/', ''], $name) . '.php');
        $docCommentHtml .= sprintf(
            "
                    <div id=\"%s\" class=\"dcms-doc-comment-container\">
                        <h1>%s</h1>
                        <p class=\"dcms-doc-comment\">%s</p>
                        <p>View on GitHub: <a style=\"font-size:.9em;\" href=\"%s\" target=\"_blank\">%s</a></p>
                    </div>",
            $htmlId,
            $name,
            str_replace('@', '<br><br>@', $docComment),
            $link,
            $link
        );
    }
    echo sprintf("<div>%s</div>", $docCommentMenu);
    echo $docCommentHtml;
    ?>
</div>
