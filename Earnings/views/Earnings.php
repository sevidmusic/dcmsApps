<?php
if (filter_input(INPUT_GET, 'ajaxRequest') === 'true') {
    require '../../../vendor/autoload.php';
    require '../functions.php';
}
$timeCard = new \Apps\Earnings\classes\TimeCard('now', new DateTimeZone('America/New_York'));
$timeCardCalculator = new \Apps\Earnings\classes\TimeCardCalculator($timeCard);
$userInterface = new \Apps\Earnings\classes\EarningsUI($timeCard);
$unRecordedHoursWorked = '93.25'; // 500 paid for 9/25/2018 through 11/14/2018 (see google drive for invoice), then 432.50 paid for 11/14/2018 - 11/25/2018 (see archived time cards)
$hoursToDate = bcadd($unRecordedHoursWorked, $timeCardCalculator->calculateTimeWorked(\Apps\Earnings\classes\TimeCardCalculator::FORMAT_HOURS), 2);
$unPaidHoursToDate = $timeCardCalculator->calculateTimeWorked(\Apps\Earnings\classes\TimeCardCalculator::FORMAT_HOURS);
// money earned/owed
$moneyEarnedToDate = calculateEarnings($hoursToDate, '10');
$moneyPaidToDate = calculateEarnings($unRecordedHoursWorked, '10.00');
$moneyOwedToDate = calculateEarnings($unPaidHoursToDate, '10');
$moneyEarnedTowardDebt = calculateEarnings($hoursToDate, '2.25');
?>
    <h1>Earnings</h1>
    <p><span class="earnings-key-text">Money Earned To Date: </span><span
            class="earnings-value-text earnings-monetary-value">$<?php echo $moneyEarnedToDate; ?></span></p>
    <p><span class="earnings-key-text">Money Paid To Date: </span><span
            class="earnings-value-text earnings-monetary-value">$<?php echo $moneyPaidToDate; ?></span></p>
    <p class="earnings-emphasized-text"><span class="earnings-key-text">Money Owed To Date: </span><span
            class="earnings-value-text earnings-monetary-value">$<?php echo $moneyOwedToDate; ?></span></p>
    <p><span class="earnings-key-text">Money Earned Toward Debt To Date: </span><span
            class="earnings-value-text earnings-monetary-value">$<?php echo $moneyEarnedTowardDebt; ?></span></p>
    <p><span class="earnings-key-text">Remaining Debt: </span><span
            class="earnings-value-text earnings-monetary-value earnings-negative-value">$<?php echo bcsub('2000', $moneyEarnedTowardDebt, 2); ?></span>
    </p>
    <h2>Calculate earnings from selected dates:</h2>
<?php
echo $userInterface->getTimeCardRangeSelector();
$specifiedEarnings = calculateEarnings($userInterface->geTimeWorkedFromSelected(), '10.00');
$specifiedEarnedTowardDebt = calculateEarnings($userInterface->geTimeWorkedFromSelected(), '2.25');

echo "<p><span class=\"earnings-key-text\">Hours worked from {$userInterface->formatTimeCardName($userInterface->getStartingTimeCardName())} to {$userInterface->formatTimeCardName($userInterface->getEndingTimeCardName())}: </span><span class=\"earnings-value-text\">" . $userInterface->geTimeWorkedFromSelected() . "</span></p>";
echo "<p><span class=\"earnings-key-text\">Money earned from {$userInterface->formatTimeCardName($userInterface->getStartingTimeCardName())} to {$userInterface->formatTimeCardName($userInterface->getEndingTimeCardName())}: </span><span class=\"earnings-value-text earnings-monetary-value\">\${$specifiedEarnings}</span></p>";
echo "<p><span class=\"earnings-key-text\">Money earned toward debt from {$userInterface->formatTimeCardName($userInterface->getStartingTimeCardName())} to {$userInterface->formatTimeCardName($userInterface->getEndingTimeCardName())}: </span><span class=\"earnings-value-text earnings-monetary-value\">\${$specifiedEarnedTowardDebt}</span></p>";
echo "<p><span class=\"earnings-key-text\">Total time worked from {$userInterface->formatTimeCardName($userInterface->getStartingTimeCardName())} to {$userInterface->formatTimeCardName($userInterface->getEndingTimeCardName())}: </span><span class=\"earnings-value-text\">{$userInterface::formatTimeForDisplay($userInterface->geTimeWorkedFromSelected(), $userInterface->geTimeWorkedFromSelected(\Apps\Earnings\classes\TimeCardCalculator::FORMAT_MINUTES))}</span></p>";
