<?php
if (filter_input(INPUT_GET, 'ajaxRequest') === 'true') {
    require '../../../vendor/autoload.php';
    require '../functions.php';
}
$timeCard = new \Apps\Earnings\classes\TimeCard('now', new DateTimeZone('America/New_York'));
$userInterface = new \Apps\Earnings\classes\EarningsUI($timeCard);

?>
    <h1>Time Worked</h1>
    <p class="earnings-emphasized-text">
        <span class="earnings-key-text">Total Unpaid Time Worked To Date: </span>
        <span class="earnings-value-text"><?php echo \Apps\Earnings\classes\EarningsUI::formatTimeForDisplay($userInterface->getUnPaidHoursToDate(), $userInterface->getUnPaidMinutesToDate()); ?></span>
        <span>(<?php echo $userInterface->getUnPaidHoursToDate(); ?> hours)</span>
    </p>
    <p>
        <span class="earnings-key-text">Total Time Worked To Date: </span>
        <span class="earnings-value-text"><?php echo \Apps\Earnings\classes\EarningsUI::formatTimeForDisplay($userInterface->getHoursToDate(), $userInterface->getMinutesWorkedToDate()); ?></span>
        <span>(<?php echo $userInterface->getHoursToDate(); ?> hours)</span>
    </p>
    <p>
        <span class="earnings-key-text">Total Time Worked Today: </span>
        <span class="earnings-value-text"><?php echo \Apps\Earnings\classes\EarningsUI::formatTimeForDisplay($userInterface->getHoursWorkedToday(), $userInterface->getMinutesWorkedToday()); ?></span>
        <span>(<?php echo $userInterface->getHoursWorkedToday(); ?> hours)</span>
    </p>
<?php
/*
<p class="earnings-emphasized-text"><span class="earnings-key-text">Hours Worked Today: </span><span
            class="earnings-value-text"><?php echo $userInterface->getHoursWorkedToday(); ?></span></p>
<p><span class="earnings-key-text">Hours Worked To Date: </span><span
                class="earnings-value-text"><?php echo $userInterface->getHoursToDate(); ?></span></p>
<p class="earnings-emphasized-text"><span class="earnings-key-text">Unpaid Hours Worked To Date: </span><span
                class="earnings-value-text"><?php echo $userInterface->getUnPaidHoursToDate(); ?></span></p>
*/
