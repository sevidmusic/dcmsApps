<?php
require 'functions.php';
$userInterface = new \Apps\Earnings\classes\EarningsUI(new \Apps\Earnings\classes\TimeCard('now', new DateTimeZone('America/New_York')));
echo $userInterface->getUserInterface();

