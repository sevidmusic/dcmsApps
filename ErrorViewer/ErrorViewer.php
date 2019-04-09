<?php

use DarlingCms\classes\staticClasses\core\CoreValues;

if (file_exists(ini_get('error_log'))) {
    $errorLog = explode(PHP_EOL, file_get_contents(ini_get('error_log')));
    if (((count($errorLog) <= 1 && count($errorLog) > 0) && $errorLog[0] === '') === false) {
        ?>
        <div id="ErrorViewer" class="dcms-admin-panel">
            <a class="ev-link" href="index.php?ErrorViewerAction=ClearErrors&ErrorLog=default">Clear Errors</a>
            <div class="dcms-clear-float"></div>
            <?php
            $numErrs = 0;
            foreach ($errorLog as $item) {
                if (empty($item) === true) {
                    continue;
                }
                $numErrs++;
            }
            ?>
            <h3>The following <?php echo strval($numErrs); ?> errors occurred:</h3>
            <?php
            $bgColors = array('#04060A', '#0D161B');
            $bgColor = $bgColors[0];
            foreach ($errorLog as $item) {
                $bgColor = ($bgColor === $bgColors[0] ? $bgColors[1] : $bgColors[0]);
                if (empty($item) === true) {
                    continue;
                }
                echo '<p class="error-viewer-error" style="background-color:' . $bgColor . ';">' . htmlentities($item) . '</p>';
            }
            if (empty(filter_input(INPUT_GET, 'ErrorViewerAction')) === false && empty(filter_input(INPUT_GET, 'ErrorLog')) === false) {
                $status = file_put_contents(ini_get('error_log'), '', LOCK_EX);
                switch (empty($status)) {
                    case false:
                        echo '<p class="dcms-negative-text ev-message">Failed to clear error log.</p>';
                        break;
                    default:
                        echo '<p class="dcms-positive-text ev-message">Cleared error log.</p>';
                        $rootUrl = CoreValues::getSiteRootUrl();
                        echo "<meta http-equiv='refresh' content='1;url={$rootUrl}' />";
                        break;
                }
            }
            ?>
        </div>
        <?php
    }
}

