<?php
require realpath('../../../core/interfaces/userInterface/IUserInterface.php');
require realpath('../classes/TimeCard.php');
require realpath('../classes/TimeCardCalculator.php');
require realpath('../classes/EarningsUI.php');
require realpath('../functions.php');

$timeCard = new \Apps\Earnings\classes\TimeCard('now', new DateTimeZone('America/New_York'));
$userInterface = new \Apps\Earnings\classes\EarningsUI($timeCard);
echo str_replace(array('Click here to move', 'draggable="true"'), array('Drag disabled', 'draggable="false"'), $userInterface->getDragHandle()); // @todo: Drag does not work for post ajax request...fix!
echo $userInterface->getMainMenu();
echo $userInterface->getEarningsClock();
echo $userInterface->getPunchButton();
if ($punchStatus = $timeCard->punch($timeCard->getCurrentTimestamp()) === true) {
    echo '<p class="dcms-positive-text earnings-msg">Logged punch on ' . $timeCard->timestampToString($timeCard->getCurrentTimestamp()) . '</p>';
}
if($punchStatus === false){
    echo '<p class="dcms-negative-text earnings-msg">Failed to log punch on ' . $timeCard->timestampToString($timeCard->getCurrentTimestamp()) . '. Please try again.</p>';
}
echo $userInterface->getCurrentView();
