<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 10/30/18
 * Time: 5:55 PM
 */

namespace Apps\AppManager\classes;

class AppInfo extends \DarlingCms\classes\info\AppInfo
{
    public function isEnabled(string $appName): bool
    {
        return true;
    }

    public function getReadme(): string
    {
        return 'This app does something to make it easier to do something. The app achives something by doing something,
                and is easy to use via the carefully designed user interface.
                Website: www.someAppSite.com
                Github: www.github.com/someUser/someApp
                ';
    }

    public function getNamespace(string $appName): string
    {
        return '\Apps\\' . $appName . '\Namespace';
    }

    public function getPath(string $appName): string
    {
        return 'Apps/' . $appName;
    }

    public function getThemes(string $appName): array
    {
        return array($appName, 'Theme A', 'Theme B', 'Theme C');
    }

    public function getJsLibraries(string $appName): array
    {
        return array(
            $appName,
            'Library A',
            'Library B',
            'Library C',
            'Library D',
            'Library E',
            'Library F',
            'Library G'
        );
    }

    public function getDemoImgPath(string $appName): string
    {
        return 'http://localhost:8888/DarlingCms/apps/AppManager/resources/images/DcmsAppManagerDefaultAppImg.png';
    }
}
