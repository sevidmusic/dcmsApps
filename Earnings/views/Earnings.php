<?php
if (filter_input(INPUT_GET, 'ajaxRequest') === 'true') {
    require '../../../vendor/autoload.php';
    require '../functions.php';
}
$timeCard = new \Apps\Earnings\classes\TimeCard('now', new DateTimeZone('America/New_York'));
$userInterface = new \Apps\Earnings\classes\EarningsUI($timeCard);
?>
    <h1>Earnings</h1>
    <p class="earnings-emphasized-text"><span class="earnings-key-text">Money Owed To Date: </span><span
                class="earnings-value-text earnings-monetary-value">$<?php echo $userInterface->getMoneyOwedToDate(); ?></span>
    </p>
    <p><span class="earnings-key-text">Money Earned To Date: </span><span
                class="earnings-value-text earnings-monetary-value">$<?php echo $userInterface->getMoneyEarnedToDate(); ?></span>
    </p>
    <p><span class="earnings-key-text">Money Earned Toward Debt To Date: </span><span
                class="earnings-value-text earnings-monetary-value">$<?php echo $userInterface->getMoneyEarnedTowardDebt(); ?></span>
    </p>
    <p><span class="earnings-key-text">Remaining Debt: </span><span
                class="earnings-value-text earnings-monetary-value earnings-negative-value">$<?php echo $userInterface->getRemainingDebt(); ?></span>
    </p>
    <h2>Calculate earnings from selected dates:</h2>
<?php
echo $userInterface->getTimeCardRangeSelector();
echo "<p><span class=\"earnings-key-text\">Hours worked from {$userInterface->formatTimeCardName($userInterface->getStartingTimeCardName())} to {$userInterface->formatTimeCardName($userInterface->getEndingTimeCardName())}: </span><span class=\"earnings-value-text\">" . $userInterface->geTimeWorkedFromSelected() . "</span></p>";
echo "<p><span class=\"earnings-key-text\">Money earned from {$userInterface->formatTimeCardName($userInterface->getStartingTimeCardName())} to {$userInterface->formatTimeCardName($userInterface->getEndingTimeCardName())}: </span><span class=\"earnings-value-text earnings-monetary-value\">\${$userInterface->getSpecifiedEarnings()}</span></p>";
echo "<p><span class=\"earnings-key-text\">Money earned toward debt from {$userInterface->formatTimeCardName($userInterface->getStartingTimeCardName())} to {$userInterface->formatTimeCardName($userInterface->getEndingTimeCardName())}: </span><span class=\"earnings-value-text earnings-monetary-value\">\${$userInterface->getSpecifiedEarnedTowardDebt()}</span></p>";
echo "<p><span class=\"earnings-key-text\">Total time worked from {$userInterface->formatTimeCardName($userInterface->getStartingTimeCardName())} to {$userInterface->formatTimeCardName($userInterface->getEndingTimeCardName())}: </span><span class=\"earnings-value-text\">{$userInterface::formatTimeForDisplay($userInterface->geTimeWorkedFromSelected(), $userInterface->geTimeWorkedFromSelected(\Apps\Earnings\classes\TimeCardCalculator::FORMAT_MINUTES))}</span></p>";
