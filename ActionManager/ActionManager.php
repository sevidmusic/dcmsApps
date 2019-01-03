<?php

use \DarlingCms\classes\staticClasses\core\CoreValues;

$actionUi = new \Apps\ActionManager\classes\ActionManagerUi();
echo $actionUi->getUserInterface();
/*
var_dump(CoreValues::getISqlQueryInstance(CoreValues::CORE_DB_HOST, CoreValues::CORE_DB_NAME, 'root', 'root'));
var_dump(CoreValues::getSiteRootUrl());
var_dump(CoreValues::getSiteRootDirPath());
var_dump(CoreValues::getJsLibRootDirPath());
var_dump(CoreValues::getJsLibDirPath('SomeLibrary'));
var_dump(CoreValues::getAppsRootDirPath());
var_dump(CoreValues::getAppDirPath('SomeApp'));
var_dump(CoreValues::getThemesRootDirPath());
var_dump(CoreValues::getThemeDirPath('Some Theme'));
*/
