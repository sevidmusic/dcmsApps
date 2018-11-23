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
