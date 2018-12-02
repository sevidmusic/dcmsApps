<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 11/13/18
 * Time: 12:41 PM
 */

namespace Apps\Earnings;


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
        return 'Earnings';
    }

    /**
     * Gets an array of the names of the themes assigned to the app.
     * @return array Array of the names of the themes assigned to the app.
     */
    public function getThemeNames(): array
    {
        return array('Earnings');
    }

    /**
     * Gets an array of the names of the javascript libraries assigned to the app.
     * @return array Array of the names of the javascript libraries assigned to the app.
     */
    public function getJsLibraryNames(): array
    {
        return array('Earnings', 'makeDraggable', 'AjaxRouter');
    }

}
