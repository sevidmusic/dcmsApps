<?php
if (file_exists(ini_get('error_log'))) {
    $errorLog = explode(PHP_EOL, file_get_contents(ini_get('error_log')));
    if (((count($errorLog) <= 1 && count($errorLog) > 0) && $errorLog[0] === '') === false) {
        ?>
        <div id="ErrorViewer" class="dcms-admin-panel dcms-make-draggable">
            <div draggable="true" id="ErrorViewerHandle" class="dcms-drag-handle">Click here to move</div>
            <a class="ev-link" href="index.php?ErrorViewerAction=ClearErrors&ErrorLog=default">Clear Errors</a>
            <div style="clear: both;"></div>
            <h3>The following errors occurred:</h3>
            <?php
            foreach ($errorLog as $item) {
                if (empty($item) === true) {
                    continue;
                }
                echo '<p style="overflow: auto;border: 3px solid #ffffff; border-radius: 20px; padding: 15px;">' . htmlentities($item) . '</p>';
            }
            if (empty(filter_input(INPUT_GET, 'ErrorViewerAction')) === false && empty(filter_input(INPUT_GET, 'ErrorLog')) === false) {
                $status = file_put_contents(ini_get('error_log'), '', LOCK_EX);
                switch (empty($status)) {
                    case false:
                        echo '<p style="position:absolute;top: 0;" class="dcms-negative-text">Failed to clear error log.</p>';
                        break;
                    default:
                        echo '<p style="position:absolute;top: 0;" class="dcms-positive-text">Cleared error log.</p>';
                        break;
                }
            }
            ?>
        </div>
        <?php
    }
}

