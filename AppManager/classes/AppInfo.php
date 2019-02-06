<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 10/30/18
 * Time: 5:55 PM
 */

namespace Apps\AppManager\classes;

use DarlingCms\classes\staticClasses\core\CoreValues;

class AppInfo extends \DarlingCms\classes\info\AppInfo
{
    /**
     * Determines the path to the specified app.
     * @param string $appName The name of the app.
     * @return string The path to the specified app.
     */
    private function determineAppPath(string $appName): string
    {
        return str_replace('AppManager/classes', $appName . '/', __DIR__);
    }

    /**
     * Determines whether or not the specified app is enabled.
     * @param string $appName The name of the app to check.
     * @return bool True if specified app is enabled, false otherwise.
     */
    public function isEnabled(string $appName): bool
    {
        if (file_exists($this->determineAppPath($appName) . $appName . '.php') === true && file_exists($this->determineAppPath($appName) . 'AppConfig.php') === true && class_exists($this->getNamespace($appName) . 'AppConfig') === true) {
            return true;
        }
        return false;
    }

    /**
     * Returns the contents of the specified app's README.md file.
     * @param string $appName The name of the app.
     * @return string The contents of the specified app's README.md file.
     */
    public function getReadme(string $appName): string
    {
        $readmePath = $this->determineAppPath($appName) . 'README.md';
        if (file_exists($readmePath)) {
            return file_get_contents($readmePath);
        }
        return 'No information available.';
    }

    /**
     * Returns the specified app's namespace.
     * @param string $appName The name of the app.
     * @return string The app's namespace.
     */
    public function getNamespace(string $appName): string
    {
        return '\\Apps\\' . $appName . '\\';
    }

    /**
     * Returns the path to the specified app.
     * @param string $appName The name of the app.
     * @return string The path to the specified app.
     */
    public function getPath(string $appName): string
    {
        return str_replace('AppManager/classes', $appName, __DIR__);
    }

    /**
     * Returns an array of the themes assigned to the specified app.
     * @param string $appName The name of the app.
     * @return array An array of the themes assigned to the specified app.
     */
    public function getThemes(string $appName): array
    {
        foreach ($this->getAppConfigObjects() as $appConfigObject) {
            if ($appConfigObject->getName() === $appName) {
                $themes = $appConfigObject->getThemeNames();
            }
        }
        return empty($themes) ? array('This app is not assigned any themes...') : $themes;
    }

    /**
     * Returns an array of the Javascript libraries assigned to the specified app.
     * @param string $appName The name of the app.
     * @return array An array of the Javascript libraries assigned to the specified app.
     */
    public function getJsLibraries(string $appName): array
    {
        foreach ($this->getAppConfigObjects() as $appConfigObject) {
            if ($appConfigObject->getName() === $appName) {
                $jsLibs = $appConfigObject->getJsLibraryNames();
            }
        }
        return empty($jsLibs) ? array('This app does not use any libraries...') : $jsLibs;
    }

    /**
     * Returns the path to the apps logo image. If the app does not provide a logo image, then
     * the path to the default logo image will be returned.
     * @param string $appName The name of the app whose logo image path should be returned.
     * @return string The path to the apps logo image, or, if the app does not provide a logo image, the path
     *                to the default logo image.
     */
    public function getDemoImgPath(string $appName): string
    {
        if (file_exists($this->getPath($appName) . '/logo.png')) {
            return CoreValues::getSiteRootUrl() . 'apps/' . $appName . '/logo.png';
            //return 'http://localhost:8888/DarlingCms/apps/' . $appName . '/logo.png';
        }
        return CoreValues::getSiteRootUrl() . 'apps/AppManager/resources/images/DcmsAppManagerDefaultAppImg.png';
        //return 'http://localhost:8888/DarlingCms/apps/AppManager/resources/images/DcmsAppManagerDefaultAppImg.png';
    }
}
