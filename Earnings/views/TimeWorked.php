<?php
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
<p>Total Time Worked To
    Date: <?php echo \Apps\Earnings\classes\EarningsUI::formatTimeForDisplay($hoursToDate, $minutesToDate); ?></p>
<p>Total Time Worked Today: <?php echo \Apps\Earnings\classes\EarningsUI::formatTimeForDisplay($hoursWorkedToday, $minutesWorkedToday); ?></p>
<p>Hours Worked To Date: <?php echo $hoursToDate; ?></p>
<p>Unpaid Hours Worked To Date: <?php echo $unPaidHoursToDate; ?></p>
<p>Hours Worked Today: <?php echo $todayHours; ?></p>
