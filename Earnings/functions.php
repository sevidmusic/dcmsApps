<?php
/**
 * Calculate earnings based on specified hourly wage and hours worked.
 * @param string $hoursWorked Number of hours worked. Note: Decimal values are allowed.
 * @param string $wage The hourly wage.
 * @return string The money earned based on the specified hours and wage.
 */
function calculateEarnings(string $hoursWorked, string $wage)
{
    return bcmul($hoursWorked, $wage, 2);
}

function renameTimeCardsMDYToYMD(): bool
{
    $filenames = scandir(__DIR__ . '/json');
    $exclude = array('.', '..', '.DS_Store', 'Archives');
    $jsonDirPath = __DIR__ . '/json/';
    $status = array();
    foreach ($filenames as $filename) {
        if (is_string($filename) === false) {
            continue;
        }
        if (!in_array($filename, $exclude, true) === true && substr($filename, -5) === '.json' && substr($filename, 0, 2) !== '20') {
            $year = substr($filename, -9, -5);
            $newName = $year . str_replace($year, '', $filename);
            $data = file_get_contents(__DIR__ . '/json/' . $filename);
            array_push($status, file_put_contents($jsonDirPath . '/new/' . $newName, $data, LOCK_EX));
        }
    }
    return !empty($status) && !in_array(false, $status, true) && !in_array(0, $status, true);
}
