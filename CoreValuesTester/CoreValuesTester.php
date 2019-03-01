<?php

use DarlingCms\classes\staticClasses\core\CoreValues;

$CoreValues = [
    'Config' =>
        [
            'CoreValues::getSiteConfigPath()' => CoreValues::getSiteConfigPath(),
            'CoreValues::getSiteConfigFilename()' => CoreValues::getSiteConfigFilename(),
            'CoreValues::getSiteConfigValue(\'DBUserName\')' => CoreValues::getSiteConfigValue('DBUserName'),
        ],
    'Paths' =>
        [
            'CoreValues::getSiteRootDirPath()' => CoreValues::getSiteRootDirPath(),
            'CoreValues::getAppsRootDirPath()' => CoreValues::getAppsRootDirPath(),
            'CoreValues::getAppDirPath(\'CoreValuesTester\')' => CoreValues::getAppDirPath('CoreValuesTester'),
            'CoreValues::getThemesRootDirPath()' => CoreValues::getThemesRootDirPath(),
            'CoreValues::getThemeDirPath(\'DCMSBase\')' => CoreValues::getThemeDirPath('DCMSBase'),
            'CoreValues::getJsLibRootDirPath()' => CoreValues::getJsLibRootDirPath(),
            'CoreValues::getJsLibDirPath(\'ARFrame\')' => CoreValues::getJsLibDirPath('ARFrame'),
        ],
    'Urls' =>
        [
            'CoreValues::getSiteRootUrl()' => CoreValues::getSiteRootUrl(),
        ],
    'Database' =>
        [
            'CoreValues::getDBHostName()' => CoreValues::getDBHostName(),
            'CoreValues::getDBUserName()' => CoreValues::getDBUserName(),
            'CoreValues::getDBPassword()' => CoreValues::getDBPassword(),
            'CoreValues::getCoreDBName()' => CoreValues::getCoreDBName(),
            'CoreValues::getAppsDBName()' => CoreValues::getAppsDBName(),
            'CoreValues::getPrivilegesDBName()' => CoreValues::getPrivilegesDBName(),
            'CoreValues::getUsersDBName()' => CoreValues::getUsersDBName(),
            'CoreValues::getPasswordsDBName()' => CoreValues::getPasswordsDBName(),
        ],
    'Misc' =>
        [
            'CoreValues::getSiteDirName()' => CoreValues::getSiteDirName(),
        ]
];
var_dump($CoreValues);
