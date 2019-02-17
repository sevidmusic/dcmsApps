<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 11/13/18
 * Time: 12:41 PM
 */

namespace Apps\Earnings;


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
