<?php
$userInterface = new \Apps\AppManager\classes\AppManagerUI(new \Apps\AppManager\classes\AppInfo());
echo $userInterface->getToolbar();
echo $userInterface->getViewLinks();
echo $userInterface->getUserInterface();
