<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 2018-11-25
 * Time: 13:25
 */

namespace Apps\AdminPanel;


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
        return 'AdminPanel';
    }

    /**
     * Gets an array of the names of the themes assigned to the app.
     * @return array Array of the names of the themes assigned to the app.
     */
    public function getThemeNames(): array
    {
        return array('AdminPanel');
    }

    /**
     * Gets an array of the names of the javascript libraries assigned to the app.
     * @return array Array of the names of the javascript libraries assigned to the app.
     */
    public function getJsLibraryNames(): array
    {
        return array('AdminPanel');
    }

}
