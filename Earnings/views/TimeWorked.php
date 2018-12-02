<?php
if (filter_input(INPUT_GET, 'ajaxRequest') === 'true') {
    require '../../../vendor/autoload.php';
    require '../functions.php';
}
$timeCard = new \Apps\Earnings\classes\TimeCard('now', new DateTimeZone('America/New_York'));
$timeCardCalculator = new \Apps\Earnings\classes\TimeCardCalculator($timeCard);
$hoursToDate = bcadd(50, $timeCardCalculator->calculateTimeWorked(\Apps\Earnings\classes\TimeCardCalculator::FORMAT_HOURS), 2);
$minutesToDate = $timeCardCalculator->calculateTimeWorked(\Apps\Earnings\classes\TimeCardCalculator::FORMAT_MINUTES);
$unPaidHoursToDate = $timeCardCalculator->calculateTimeWorked(\Apps\Earnings\classes\TimeCardCalculator::FORMAT_HOURS);
$todayHours = $timeCardCalculator->calculateTimeWorked(\Apps\Earnings\classes\TimeCardCalculator::FORMAT_HOURS, array(\Apps\Earnings\classes\TimeCardCalculator::OPTION_SELECTED => array($timeCard->getCurrentTimeCardName())));

$hoursWorkedToday = $timeCardCalculator->calculateTimeWorked(\Apps\Earnings\classes\TimeCardCalculator::FORMAT_HOURS, array(\Apps\Earnings\classes\TimeCardCalculator::OPTION_SELECTED => array($timeCard->getCurrentTimeCardName())));
$minutesWorkedToday = $timeCardCalculator->calculateTimeWorked(\Apps\Earnings\classes\TimeCardCalculator::FORMAT_MINUTES, array(\Apps\Earnings\classes\TimeCardCalculator::OPTION_SELECTED => array($timeCard->getCurrentTimeCardName())));
$secondsWorkedToday = $timeCardCalculator->calculateTimeWorked(\Apps\Earnings\classes\TimeCardCalculator::FORMAT_SECONDS, array(\Apps\Earnings\classes\TimeCardCalculator::OPTION_SELECTED => array($timeCard->getCurrentTimeCardName())));

?>
<h1>Time Worked</h1>
<p><span class="earnings-key-text">Total Time Worked To
        Date: </span><span
            class="earnings-value-text"><?php echo \Apps\Earnings\classes\EarningsUI::formatTimeForDisplay($hoursToDate, $minutesToDate); ?></span>
</p>
<p><span class="earnings-key-text">Total Time Worked
        Today: </span><span
            class="earnings-value-text"><?php echo \Apps\Earnings\classes\EarningsUI::formatTimeForDisplay($hoursWorkedToday, $minutesWorkedToday); ?></span>
</p>
<p><span class="earnings-key-text">Hours Worked To Date: </span><span
            class="earnings-value-text"><?php echo $hoursToDate; ?></span></p>
<p class="earnings-emphasized-text"><span class="earnings-key-text">Unpaid Hours Worked To Date: </span><span
            class="earnings-value-text"><?php echo $unPaidHoursToDate; ?></span></p>
<p class="earnings-emphasized-text"><span class="earnings-key-text">Hours Worked Today: </span><span
            class="earnings-value-text"><?php echo $todayHours; ?></span></p>
