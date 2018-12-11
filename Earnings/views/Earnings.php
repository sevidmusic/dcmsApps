<?php
if (filter_input(INPUT_GET, 'ajaxRequest') === 'true') {
    require '../../../vendor/autoload.php';
    require '../functions.php';
}
// init
$timeCard = new \Apps\Earnings\classes\TimeCard('now', new DateTimeZone('America/New_York'));
$timeCardCalculator = new \Apps\Earnings\classes\TimeCardCalculator($timeCard);
$userInterface = new \Apps\Earnings\classes\EarningsUI($timeCard);

/**
 * Calculations
 */
// time worked
$hoursToDate = $timeCardCalculator->calculateTimeWorked(\Apps\Earnings\classes\TimeCardCalculator::FORMAT_HOURS, [\Apps\Earnings\classes\TimeCardCalculator::OPTION_RANGE => [$userInterface->getOldestTimeCardName(), $userInterface->getNewestTimeCardName()]]);
$paidHoursToDate = $timeCardCalculator->calculateTimeWorked(\Apps\Earnings\classes\TimeCardCalculator::FORMAT_HOURS, [\Apps\Earnings\classes\TimeCardCalculator::OPTION_RANGE => [$userInterface->getOldestTimeCardName(), '12032018']]); // @todo ! Implement marking time cards as paid or unpiad so hardcoded ending time card name value not necessary
$unPaidHoursToDate = $timeCardCalculator->calculateTimeWorked(\Apps\Earnings\classes\TimeCardCalculator::FORMAT_HOURS, [\Apps\Earnings\classes\TimeCardCalculator::OPTION_RANGE => ['12102018', $userInterface->getEndingTimeCardName()]]); // @todo ! Implement marking time cards as paid or unpiad so hardcoded starting time card name value not necessary
// money earned/paid/owed/debt
$moneyEarnedToDate = calculateEarnings($hoursToDate, '10.00');
$moneyEarnedTowardDebt = calculateEarnings($hoursToDate, '2.50');
$moneyPaidToDate = calculateEarnings($paidHoursToDate, '10.00');
$moneyOwedToDate = calculateEarnings($unPaidHoursToDate, '10.00');
$remainingDebt = bcsub('2000.00', $moneyEarnedTowardDebt, 2);
// specified calculations | based on selected time card range as set by the TimeCardRangeSelector.
$specifiedEarnings = calculateEarnings($userInterface->geTimeWorkedFromSelected(), '10.00');
$specifiedEarnedTowardDebt = calculateEarnings($userInterface->geTimeWorkedFromSelected(), '2.50');
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
                class="earnings-value-text earnings-monetary-value earnings-negative-value">$<?php echo $remainingDebt; ?></span>
    </p>
    <h2>Calculate earnings from selected dates:</h2>
<?php
echo $userInterface->getTimeCardRangeSelector();
echo "<p><span class=\"earnings-key-text\">Hours worked from {$userInterface->formatTimeCardName($userInterface->getStartingTimeCardName())} to {$userInterface->formatTimeCardName($userInterface->getEndingTimeCardName())}: </span><span class=\"earnings-value-text\">" . $userInterface->geTimeWorkedFromSelected() . "</span></p>";
echo "<p><span class=\"earnings-key-text\">Money earned from {$userInterface->formatTimeCardName($userInterface->getStartingTimeCardName())} to {$userInterface->formatTimeCardName($userInterface->getEndingTimeCardName())}: </span><span class=\"earnings-value-text earnings-monetary-value\">\${$specifiedEarnings}</span></p>";
echo "<p><span class=\"earnings-key-text\">Money earned toward debt from {$userInterface->formatTimeCardName($userInterface->getStartingTimeCardName())} to {$userInterface->formatTimeCardName($userInterface->getEndingTimeCardName())}: </span><span class=\"earnings-value-text earnings-monetary-value\">\${$specifiedEarnedTowardDebt}</span></p>";
echo "<p><span class=\"earnings-key-text\">Total time worked from {$userInterface->formatTimeCardName($userInterface->getStartingTimeCardName())} to {$userInterface->formatTimeCardName($userInterface->getEndingTimeCardName())}: </span><span class=\"earnings-value-text\">{$userInterface::formatTimeForDisplay($userInterface->geTimeWorkedFromSelected(), $userInterface->geTimeWorkedFromSelected(\Apps\Earnings\classes\TimeCardCalculator::FORMAT_MINUTES))}</span></p>";
