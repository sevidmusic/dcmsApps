<?php
/**
 * Created by Sevi Donnelly Foreman.
 * Date: 10/17/18
 * Time: 4:03 AM
 */

namespace Apps\AppManager;

use DarlingCms\abstractions\accessControl\AAdminAppConfig;
use DarlingCms\interfaces\accessControl\IAppConfig;

class AppConfig extends AAdminAppConfig implements IAppConfig
{
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
        return array('DCMSBase', 'AppManager');
    }

    /**
     * Gets an array of the names of the javascript libraries assigned to the app.
     * @return array Array of the names of the javascript libraries assigned to the app.
     */
    public function getJsLibraryNames(): array
    {
        return array('makeDraggable', 'AjaxRouter', 'AppManager', 'XDebugUI');
    }

    /**
     * Returns an array of the names of the Roles that are required by this app.
     * ALL IMPLEMENTATIONS OF THIS CLASS MUST IMPLEMENT THIS METHOD!
     * @return array Array of the names of the Roles that are required by this app.
     */
    protected function defineValidRoles(): array
    {
        return array('Administrator');
    }
}
