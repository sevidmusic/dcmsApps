<?php
$timeCard = new \Apps\Earnings\classes\TimeCard('now', new DateTimeZone('America/New_York'));
$inOut = '';

?>
<h1>Punches</h1>
<?php
foreach ($timeCard->getTimeCardNames() as $timeCardName) {
    echo "<h3>{$timeCardName[0]}{$timeCardName[1]}/{$timeCardName[2]}{$timeCardName[3]}/{$timeCardName[4]}{$timeCardName[5]}{$timeCardName[6]}{$timeCardName[7]}</h3>";
    foreach ($timeCard->getTimeCardData($timeCardName) as $punch) {
        $inOut = ($inOut === 'Punch In' ? 'Punch Out' : 'Punch In');
        echo '<p>' . $timeCard->timestampToString($punch) . ' ' . $inOut . '</p>';
    }
}
?>
