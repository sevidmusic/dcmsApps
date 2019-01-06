<?php
if (filter_input(INPUT_GET, 'ajaxRequest') === 'true') {
    require '../../../vendor/autoload.php';
    require '../functions.php';
}
$timeCard = new \Apps\Earnings\classes\TimeCard('now', new DateTimeZone('America/New_York'));
$timeCardCalculator = new \Apps\Earnings\classes\TimeCardCalculator($timeCard);
$hoursToDate = $timeCardCalculator->calculateTimeWorked(\Apps\Earnings\classes\TimeCardCalculator::FORMAT_HOURS);
$minutesToDate = $timeCardCalculator->calculateTimeWorked(\Apps\Earnings\classes\TimeCardCalculator::FORMAT_MINUTES);
$unPaidHoursToDate = bcsub($hoursToDate, '0.00', 2); // USE INVOICE PANEL TO MANUALLY CALCULATE RIGHT OPERAND USING THE OLDEST DATE TO THE LAST DAY OF THE LAST PAY PERIOD | i.e. unpaid punches/time cards
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
<!-- @todo  Refactor Unpaid hours calculations to be accurate to real time.*EOL*Also, their should probably be a methods added to either the TimeCardCalculator class for the most used getTime*() logic,*EOL*i.e.,*EOL*getTimeTotal(string $format = 'hours')*EOL*getTimeUnpaid(string $format = 'hours')*EOL*getTimePaid(string $format = 'hours')*EOL*
 -->
<!-- @todo UNPAID HOURS IS NOT ACCURATE AT THE MOMENT UNLESS SET MANUALLY! THIS NEEDS TO BE FIXED!
-->
<p class="earnings-emphasized-text"><span class="earnings-key-text">Unpaid Hours Worked To Date: </span><span
            class="earnings-value-text"><?php echo $unPaidHoursToDate; ?></span></p>
<p class="earnings-emphasized-text"><span class="earnings-key-text">Hours Worked Today: </span><span
            class="earnings-value-text"><?php echo $todayHours; ?></span></p>
