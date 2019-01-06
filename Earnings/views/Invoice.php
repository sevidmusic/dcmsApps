<?php
if (filter_input(INPUT_GET, 'ajaxRequest') === 'true') {
    require '../../../vendor/autoload.php';
    require '../functions.php';
}
$timeCard = new \Apps\Earnings\classes\TimeCard('now', new DateTimeZone('America/New_York'));
$timeCardCalculator = new \Apps\Earnings\classes\TimeCardCalculator($timeCard);
$userInterface = new \Apps\Earnings\classes\EarningsUI($timeCard);
$inOut = '';
$timeCardNames = $userInterface->getTimeCardNames();
$firstTimeCardName = array_shift($timeCardNames);
$lastTimeCardName = array_pop($timeCardNames);
$totalHoursWorked = $userInterface->geTimeWorkedFromSelected();
?>
<h1>Invoice</h1>
<?php echo $userInterface->getTimeCardRangeSelector(); ?>
<p>Total Hours Worked: <?php echo $totalHoursWorked; ?></p>
<p>Total Money Earned (Due): $<?php echo calculateEarnings($totalHoursWorked, '10'); ?></p>
<p>Total Money Earned Toward Debt: $<?php echo bcadd(calculateEarnings($totalHoursWorked, '2.50'), '695.13', 2); ?></p>
<table class="timeCard-invoice-table">
    <tr class="timeCard-invoice-table-tr">
        <th class="timeCard-invoice-table-th">Date</th>
        <th class="timeCard-invoice-table-th">Punches</th>
        <th class="timeCard-invoice-table-th">Total Time Worked</th>
        <th class="timeCard-invoice-table-th">Money Earned On This Date</th>
        <th class="timeCard-invoice-table-th">Money Earned Toward Debt On This Date</th>
    </tr>
    <?php foreach ($userInterface->getTimeCardNames() as $timeCardName) { ?>
        <tr class="timeCard-invoice-table-tr">
            <td class="timeCard-invoice-table-td timeCard-invoice-table-date-col">
                <?php echo "{$timeCardName[0]}{$timeCardName[1]}/{$timeCardName[2]}{$timeCardName[3]}/{$timeCardName[4]}{$timeCardName[5]}{$timeCardName[6]}{$timeCardName[7]}"; ?>
            </td>
            <td class="timeCard-invoice-table-td timeCard-invoice-table-punches-col">
                <ul>
                    <?php
                    foreach ($timeCard->getTimeCardData($timeCardName) as $punch) {
                        $inOut = ($inOut === 'Punch In' ? 'Punch Out' : 'Punch In');
                        echo '<li>' . $timeCard->timestampToString($punch) . ' ' . $inOut . '</li>';
                    }
                    ?>
                </ul>
            </td>
            <td class="timeCard-invoice-table-td timeCard-invoice-table-timeWorked-col">
                <?php echo ' ' . $timeCardCalculator->calculateTimeCard($timeCardName, 'hours'); ?>
                <!-- <ul>
                    <li>Hours: <?php echo ' ' . $timeCardCalculator->calculateTimeCard($timeCardName, 'hours'); ?></li>
                    <li>Total
                        Time: <?php echo \Apps\Earnings\classes\EarningsUI::formatTimeForDisplay($timeCardCalculator->calculateTimeCard($timeCardName, 'hours'), $timeCardCalculator->calculateTimeCard($timeCardName, 'minutes')); ?></li>
                </ul> -->
            </td>
            <td class="timeCard-invoice-table-td timeCard-invoice-table-moneyEarned-col">
                <?php echo '$' . calculateEarnings($timeCardCalculator->calculateTimeCard($timeCardName, 'hours'), '10'); ?>
            </td>
            <td class="timeCard-invoice-table-td timeCard-invoice-table-moneyEarnedTowardDebt-col">
                <?php echo '$' . calculateEarnings($timeCardCalculator->calculateTimeCard($timeCardName, 'hours'), '2.50'); ?>
            </td>
        </tr>
    <?php } ?>
</table>

