<?php

use DarlingCms\classes\html\HtmlBlock;
use DarlingCms\classes\html\HtmlContainer;
use DarlingCms\classes\html\HtmlTag;
use DarlingCms\classes\staticClasses\core\CoreValues;

$coreValues = [
    'Config' =>
        [
            'CoreValues::getSiteConfigPath()' => CoreValues::getSiteConfigPath(),
            'CoreValues::getSiteConfigFilename()' => CoreValues::getSiteConfigFilename(),
            'CoreValues::getSiteConfigValue(\'CoreDBName\')' => CoreValues::getSiteConfigValue('CoreDBName'),
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
            'CoreValues::getDBHostName(CoreValues::getCoreDBName())' => CoreValues::getDBHostName(CoreValues::getCoreDBName()),
            'CoreValues::getDBUserName(CoreValues::getAppsDBName())' => CoreValues::getDBUserName(CoreValues::getAppsDBName()),
            'CoreValues::getDBPassword(CoreValues::getPrivilegesDBName())' => CoreValues::getDBPassword(CoreValues::getPrivilegesDBName()),
            'CoreValues::getCoreDBName()' => CoreValues::getCoreDBName(),
            'CoreValues::getAppsDBName()' => CoreValues::getAppsDBName(),
            'CoreValues::getPrivilegesDBName()' => CoreValues::getPrivilegesDBName(),
            'CoreValues::getUsersDBName()' => CoreValues::getUsersDBName(),
            'CoreValues::getPasswordsDBName()' => CoreValues::getPasswordsDBName(),
        ],
    'Misc' =>
        [
            'CoreValues::getSiteDirName()' => CoreValues::getSiteDirName(),
            'CoreValues::getSiteUrlName()' => CoreValues::getSiteUrlName(),
            'CoreValues::siteConfigured()' => CoreValues::siteConfigured(),
        ]
];
$coreValuesTableDiv = new HtmlContainer(new HtmlBlock(), 'div', ['id' => 'CoreValuesTester', 'class' => 'dcms-admin-panel dcms-admin-panel-pos1 dcms-make-draggable']);//'style' => 'width:90%;margin:0 auto;height:420px;overflow:auto;'
// <div id="AppManagerHandle" draggable="true" class="dcms-drag-handle">Click here to move...</div>
$coreValuesTableDiv->addHtml(new HtmlTag('div', ['id' => 'CoreValuesTesterHandle', 'class' => 'dcms-drag-handle'], 'Click here to move...'));
$coreValuesTable = new HtmlContainer(new HtmlBlock(), 'table', ['style' => 'table-layout: fixed; width: 100%;']);
foreach ($coreValues as $category => $values) {
    $row = new HtmlContainer(new HtmlBlock(), 'tr', ['class' => 'dcms-table-row']);
    $row->addHtml(new HtmlTag('th', ['style' => 'overflow:auto;text-align:left;padding:20px;', 'class' => 'dcms-table-th'], $category));
    $coreValuesTable->addHtml($row);
    foreach ($values as $valueName => $value) {
        $vRow = new HtmlContainer(new HtmlBlock(), 'tr', ['class' => 'dcms-table-row']);
        $vRow->addHtml(new HtmlTag('td', ['style' => 'overflow:auto;border:3px double #ffffff;border-radius:20px;padding:20px;background:#555555;', 'class' => 'dcms-table-td'], $valueName));
        $vRow->addHtml(new HtmlTag('td', ['style' => 'overflow:auto;border:3px double #ffffff;border-radius:20px;padding:20px;', 'class' => 'dcms-table-td'], $value));
        $coreValuesTable->addHtml($vRow);
    }
}
$coreValuesTableDiv->addHtml($coreValuesTable);
echo $coreValuesTableDiv->getHtml();
