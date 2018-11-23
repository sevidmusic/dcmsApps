<?php
$timeCard = new \Apps\Earnings\classes\TimeCard('now', new DateTimeZone('America/New_York'));
$timeCardCalculator = new \Apps\Earnings\classes\TimeCardCalculator($timeCard);
$hoursToDate = bcadd(50, $timeCardCalculator->calculateTimeWorked(\Apps\Earnings\classes\TimeCardCalculator::FORMAT_HOURS), 2);
$unPaidHoursToDate = $timeCardCalculator->calculateTimeWorked(\Apps\Earnings\classes\TimeCardCalculator::FORMAT_HOURS);
// money earned/owed
$moneyOwedToDate = calculateEarnings($unPaidHoursToDate, '10');
$moneyEarnedToDate = calculateEarnings($hoursToDate, '10');
$moneyEarnedTowardDebt = calculateEarnings($hoursToDate, '2.25');
?>
<h1>Earnings</h1>
<p>Money Earned To Date:<span style="color: olivedrab;">$<?php echo $moneyEarnedToDate; ?></span></p>
<p>Money Paid To Date: <span style="color: olivedrab;">$500</span></p>
<p>Money Owed To Date: <span style="color: olivedrab;">$<?php echo $moneyOwedToDate; ?></span></p>
<p>Money Earned Toward Debt To Date: <span style="color: olivedrab;">$<?php echo $moneyEarnedTowardDebt; ?></span></p>
<p>Remaining Debt: <span style="color: darkred;">$<?php echo bcsub(2000, $moneyEarnedTowardDebt, 2); ?></span></p>
