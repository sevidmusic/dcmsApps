<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 11/16/18
 * Time: 4:32 PM
 */

namespace Apps\ARFrame;

use DarlingCms\interfaces\accessControl\IAppConfig;

class AppConfig implements IAppConfig
{
    /**
     * Validates access.
     * @return bool True if access is valid, false otherwise.
     */
    public function validateAccess(): bool
    {
        if (filter_input(INPUT_GET, 'ar') === 'on') {
            return true;
        }
        return false;
    }

    /**
     * Gets the app's name.
     * @return string The app's name.
     */
    public function getName(): string
    {
        return 'ARFrame';
    }

    /**
     * Gets an array of the names of the themes assigned to the app.
     * @return array Array of the names of the themes assigned to the app.
     */
    public function getThemeNames(): array
    {
        return array('ARFrame');
    }

    /**
     * Gets an array of the names of the javascript libraries assigned to the app.
     * @return array Array of the names of the javascript libraries assigned to the app.
     */
    public function getJsLibraryNames(): array
    {
        return array('ARFrame');
    }
}
