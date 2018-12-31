<?php
/**
 * Created by Sevi Donnelly Foreman.
 * Date: 10/17/18
 * Time: 4:03 AM
 */

namespace Apps\AppManager;

use DarlingCms\interfaces\accessControl\IAppConfig;

class AppConfig implements IAppConfig
{
    /**
     * Validates access.
     * @return bool True if access is valid, false otherwise.
     */
    public function validateAccess(): bool
    {
        return true;
    }

    /**
     * Gets the app's name.
     * @return string The app's name.
     */
    public function getName(): string
    {
        return 'AppManager';
    }

    /**
     * Gets an array of the names of the themes assigned to the app.
     * @return array Array of the names of the themes assigned to the app.
     */
    public function getThemeNames(): array
    {
        return array('DCMSBase', 'AppManagerDark');
    }

    /**
     * Gets an array of the names of the javascript libraries assigned to the app.
     * @return array Array of the names of the javascript libraries assigned to the app.
     */
    public function getJsLibraryNames(): array
    {
        return array('makeDraggable', 'AjaxRouter', 'AppManager', 'XDebugUI');
    }
}
