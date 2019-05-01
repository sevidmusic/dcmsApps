<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 11/11/18
 * Time: 6:39 PM
 */

namespace Apps\AppManager\classes;


use DarlingCms\classes\FileSystem\ZipCrud;
use DarlingCms\classes\staticClasses\core\CoreValues;

class DisabledAppInfo extends AppInfo
{
    private $zipCrud;

    /**
     * AppInfo constructor. Sets the startup mode, determines the path to the apps directory, determines
     * the paths to each app's AppConfig.php file, determines each app's namespace, and instantiates the
     * appropriate IAppStartup implementation for each app based on the $startupMode.
     * @param int $startupMode The mode to use for startup. This parameter determines which IAppStartup
     *                         implementation will be instantiated for each app. Supported modes are
     *                         represented by the following class constants:
     *
     *          - AppInfo::STARTUP_DEFAULT    Use the AppStartup implementation of the IAppStartup interface,
     *                                        which does not use a caching mechanism. (Default)
     *
     *         - AppInfo::STARTUP_JSON_CACHE Use the AppStartupJsonCache implementation of the IAppStartup
     *                                        interface, which uses JSON as a caching mechanism.
     * @param string ...$excludeApp Name of the app(s) that should be excluded from this AppInfo instance.
     *                              Note: To set more then one app to be excluded from this App Info instance,
     *                              pass additional app names as additional parameters.
     * @see $startupMode
     * @see $excludedApps
     * @see AppInfo::excludeApp()
     * @see AppInfo::setAppConfigPaths()
     * @see AppInfo::setAppNamespaces()
     * @see AppInfo::setAppConfigObjects()
     * @see AppInfo::setAppStartupObjects()
     */
    public function __construct(ZipCrud $zipCrud, int $startupMode = 2, string  ...$excludeApp)
    {
        $this->zipCrud = $zipCrud;
        $excludedApps = array_merge($excludeApp, glob(str_replace('/AppManager/classes', '', __DIR__) . '/*[!.zip]'));
        foreach ($excludedApps as $index => $appPath) {
            $appName = pathinfo($appPath, PATHINFO_FILENAME);
            $this->excludeApp($appName);
        }
        foreach ($this->getDisabledAppPaths() as $zipFilePath) {
            $extractionPath = str_replace('.zip', '', $zipFilePath);
            $this->zipCrud->extractFileFromZip($zipFilePath, $extractionPath, 'AppConfig.php');
            //var_dump((file_exists($extractionPath . '/AppConfig.php') === true ? 'Temp AppConfig exists' : 'Temp AppConfig does not exist'));
        }
        parent::__construct($startupMode);
    }

    /**
     * Cleans up any disabled app temp directories and AppConfig.php files created during object lifetime.
     */
    public function __destruct()
    {
        foreach ($this->getDisabledAppPaths() as $zipFilePath) {
            $extractionPath = str_replace('.zip', '', $zipFilePath);
            ZipCrud::removeDir($extractionPath);
        }
    }

    public function getDisabledAppPaths(): array
    {
        return glob(str_replace('/AppManager/classes', '', __DIR__) . '/*.zip');
    }

    /**
     * Returns the contents of the specified app's README.md file.
     * @param string $appName The name of the app.
     * @return string The contents of the specified app's README.md file.
     */
    public function getReadme(string $appName): string
    {
        return ($this->zipCrud->zipHasFile($this->getPath($appName), 'README.md') === true ? $this->zipCrud->readFileFromZip($this->getPath($appName), 'README.md') : 'No Information Available...');
    }

    /**
     * Returns the path to the specified disabled app's zip file.
     * @param string $appName The name of the disabled app whose zip's file path should be returned.
     * @return string The path to the specified disabled app's zip file.
     */
    public function getPath(string $appName): string
    {
        return $path = str_replace('/AppManager/classes', '', __DIR__) . '/' . $appName . '.zip';
    }

    /**
     * Returns the path to the apps logo image. If the app does not provide a logo image, then
     * the path to the default logo image will be returned.
     * Note: This implementation deals with apps that have been archived, i.e. disabled. Therefore, if the
     * app provides a logo image, and a temp logo image for the app does not exist, one will be generated from
     * the zipped logo image.
     * @param string $appName The name of the app whose logo image path should be returned.
     * @return string The path to the apps logo image, or, if the app does not provide a logo image, the path
     *                to the default logo image.
     * @todo: if ZipCrud is used in the future, make sure to use it's extractFileFromZip() method to create the temp copy of the logo img
     */
    public function getDemoImgPath(string $appName): string
    {
        if (file_exists($this->getTempImgPath($appName)) === false) {
            $this->generateTempImage($appName);
            return CoreValues::getSiteRootUrl() . 'apps/AppManager/resources/images/DcmsAppManagerDefaultAppImg.png';

        }
        return CoreValues::getSiteRootUrl() . 'apps/AppManager/resources/images/' . $appName . '.logo.png';
    }

    // @here @todo Fix, images are not being generated, perahps used ZipCrud method like ZipCrud::readFileFromZip()...
    private function generateTempImage(string $appName): bool
    {
        $zipFilePath = CoreValues::getAppDirPath($appName) . '.zip';
        $fileName = 'logo.png';
        if ($this->zipHasFile($zipFilePath, $fileName) === false) {
            error_log('Disabled App Info Error: Failed to generate temp image for app ' . $appName . ' The archive does not contain an image at stream ' . $this->getLogoImgStreamPath($appName));
            return false;
        }
        return empty(file_put_contents($this->getTempImgPath($appName), $this->zipCrud->readFileFromZip($zipFilePath, $fileName)));
    }

    // @todo Move to ZipFileCrud if it works and make it a public method...
    private function zipHasFile(string $zipFilePath, string $fileName): bool
    {
        if (!empty($this->zipCrud->readFileFromZip($zipFilePath, $fileName))) {
            return true;
        }
        return false;
    }

    private function getTempImgFileName(string $appName): string
    {
        return $appName . '.logo.png';
    }

    /**
     * Get's the zip stream path for the specified app.
     * @param string $appName The name of the app.
     * @param string $fileName (Optional) The name of the file in the archive to get a stream path for.
     * @return string The stream path.
     */
    private function getZipStreamPath(string $appName, string $fileName = ''): string
    {
        return str_replace('///', '//', 'zip://' . $this->getPath($appName) . ($fileName !== '' ? '#' . $fileName : ''));
    }

    private function getLogoImgStreamPath(string $appName): string
    {
        return $this->getZipStreamPath($appName, 'logo.png');
    }

    private function getImgDirPath()
    {
        return str_replace('classes', 'resources/images', __DIR__);
    }

    private function getTempImgPath(string $appName)
    {
        return $this->getImgDirPath() . '/' . $this->getTempImgFileName($appName);
    }
}
