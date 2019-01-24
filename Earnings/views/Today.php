<?php
if (filter_input(INPUT_GET, 'ajaxRequest') === 'true') {
    require '../../../vendor/autoload.php';
    require '../functions.php';
}
$timeCard = new \Apps\Earnings\classes\TimeCard('now', new DateTimeZone('America/New_York'));
$timeCardCalculator = new \Apps\Earnings\classes\TimeCardCalculator($timeCard);
$hoursWorked = $timeCardCalculator->calculateTimeWorked(\Apps\Earnings\classes\TimeCardCalculator::FORMAT_HOURS, array(\Apps\Earnings\classes\TimeCardCalculator::OPTION_SELECTED => array($timeCard->getCurrentTimeCardName())));
$minutesWorked = $timeCardCalculator->calculateTimeWorked(\Apps\Earnings\classes\TimeCardCalculator::FORMAT_MINUTES, array(\Apps\Earnings\classes\TimeCardCalculator::OPTION_SELECTED => array($timeCard->getCurrentTimeCardName())));
?>
    <h1>Today</h1>
    <p>
        <span class="earnings-key-text">Time worked today:</span>
        <span class="earnings-value-text"><?php echo \Apps\Earnings\classes\EarningsUI::formatTimeForDisplay($hoursWorked, $minutesWorked); ?>
            <span style="color: #5786aa;">
                <span style="color: #ffffff;margin-left: 10px;margin-right: 5px;"> | </span><?php echo $hoursWorked; ?> hours
            </span>
            <span style="clear: both;"></span>
        </span>
    </p>
    <p>
        <span class="earnings-key-text">Money earned today:</span>
        <span class="earnings-monetary-value">$<?php echo calculateEarnings($hoursWorked, '10.00'); ?></span>
    </p>
    <p>Money earned toward debt today:
        <span class="earnings-monetary-value">$<?php echo calculateEarnings($hoursWorked, '2.50'); ?></span>
    </p>
    <h3>Today's Punches</h3>
<?php
$inOut = '';
foreach ($timeCard->getTimeCardData() as $punch) {
    $inOut = ($inOut === 'Punch In' ? 'Punch Out' : 'Punch In');
    echo '<p>' . $timeCard->timestampToString($punch) . ' ' . $inOut . '</p>';
}
