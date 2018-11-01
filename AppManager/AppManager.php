<?php
$appInfo = new \Apps\AppManager\classes\AppInfo(\Apps\AppManager\classes\AppInfo::STARTUP_DEFAULT, 'AppManager');
$userInterface = new \Apps\AppManager\classes\AppManagerUI($appInfo);
echo $userInterface->getToolbar();
echo $userInterface->getViewLinks();
echo $userInterface->getUserInterface();
