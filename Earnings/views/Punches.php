<?php
if (filter_input(INPUT_GET, 'ajaxRequest') === 'true') {
    require '../../../vendor/autoload.php';
    require '../functions.php';
}
$timeCard = new \Apps\Earnings\classes\TimeCard('now', new DateTimeZone('America/New_York'));
$userInterface = new \Apps\Earnings\classes\EarningsUI($timeCard);
$inOut = '';
?>
<h1>Punches</h1>
<?php
echo $userInterface->getTimeCardRangeSelector();
foreach ($userInterface->getTimeCardNames() as $timeCardName) {
    echo "<h3>{$userInterface->formatTimeCardName($timeCardName)}</h3>";
    foreach ($timeCard->getTimeCardData($timeCardName) as $punch) {
        $inOut = ($inOut === 'Punch In' ? 'Punch Out' : 'Punch In');
        echo '<p>' . $timeCard->timestampToString($punch) . ' ' . $inOut . '</p>';
    }
}
?>
