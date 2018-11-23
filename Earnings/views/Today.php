<?php
$timeCard = new \Apps\Earnings\classes\TimeCard('now', new DateTimeZone('America/New_York'));
$timeCardCalculator = new \Apps\Earnings\classes\TimeCardCalculator($timeCard);
$hoursWorked = $timeCardCalculator->calculateTimeWorked(\Apps\Earnings\classes\TimeCardCalculator::FORMAT_HOURS, array(\Apps\Earnings\classes\TimeCardCalculator::OPTION_SELECTED => array($timeCard->getCurrentTimeCardName())));
$minutesWorked = $timeCardCalculator->calculateTimeWorked(\Apps\Earnings\classes\TimeCardCalculator::FORMAT_MINUTES, array(\Apps\Earnings\classes\TimeCardCalculator::OPTION_SELECTED => array($timeCard->getCurrentTimeCardName())));
$secondsWorked = $timeCardCalculator->calculateTimeWorked(\Apps\Earnings\classes\TimeCardCalculator::FORMAT_SECONDS, array(\Apps\Earnings\classes\TimeCardCalculator::OPTION_SELECTED => array($timeCard->getCurrentTimeCardName())));
?>
    <h1>Today</h1>
    <p>Time worked
        today: <?php echo \Apps\Earnings\classes\EarningsUI::formatTimeForDisplay($hoursWorked, $minutesWorked); ?></p>
    <p style="font-size: .8em;">Total Hours worked today: <?php echo $hoursWorked; ?></p>
    <p style="font-size: .8em;">Total Minutes worked today: <?php echo $minutesWorked; ?></p>
    <p style="font-size: .8em;">Total Seconds worked today: <?php echo $secondsWorked; ?></p>
    <p>Money earned today: <span style="color: #61ffb0;">$<?php echo calculateEarnings($hoursWorked, '10.00'); ?></span>
    </p>
    <p>Money earned toward debt today: <span
                style="color: #61ffb0;">$<?php echo calculateEarnings($hoursWorked, '2.25'); ?></span></p>
    <h3>Today's Punches</h3>
<?php
$inOut = '';
foreach ($timeCard->getTimeCardData() as $punch) {
    $inOut = ($inOut === 'Punch In' ? 'Punch Out' : 'Punch In');
    echo '<p>' . $timeCard->timestampToString($punch) . ' ' . $inOut . '</p>';
}
