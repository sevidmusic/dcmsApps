<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 11/13/18
 * Time: 1:20 PM
 */

namespace Apps\Earnings\classes;


use DateTimeZone;

class TimeCard extends \DateTime
{
    public const OPTION_RANGE = 'range';
    public const OPTION_SELECTED = 'selected';
    private $jsonDirPath;

    /**
     * @param string $time
     * @param DateTimeZone $timezone
     * @link https://php.net/manual/en/datetime.construct.php
     */
    public function __construct(string $time = 'now', DateTimeZone $timezone = null)
    {
        parent::__construct($time, $timezone);
        $this->jsonDirPath = pathinfo(__DIR__, PATHINFO_DIRNAME) . '/json';
        if (is_dir($this->jsonDirPath) === false) {
            mkdir($this->jsonDirPath);
        }
        if (file_exists($this->getCurrentTimeCardPath()) === false) {
            file_put_contents($this->getCurrentTimeCardPath(), json_encode(array()), LOCK_EX);
        }
    }

    public function getCurrentTimeCardPath()
    {
        return $this->jsonDirPath . '/' . $this->getCurrentTimeCardName() . '.json';
    }

    public function getCurrentTimeCardName(): string
    {
        return $this->timestampToString($this->getCurrentTimestamp(), 'mdY');
    }

    public function getCurrentTimestamp()
    {
        return strtotime($this->format('Y-m-d H:i:s'));
    }

    public function punch(int $timestamp): bool
    {// make sure to check that the timestamp is greater then the last logged timestamp
        $timestamps = $this->getTimeCardData();
        array_push($timestamps, $timestamp);
        return $this->setTimeCardData($this->getCurrentTimeCardName(), $timestamps);
    }

    public function timestampToString(int $timestamp, string $format = 'm/d/Y h:i:s A')
    {
        return gmdate($format, $timestamp);
    }

    public function getTimeCardData(string $timeCardName = '')
    {
        $timeCardPath = $timeCardName !== '' ? $this->jsonDirPath . '/' . $timeCardName . '.json' : $this->getCurrentTimeCardPath();
        if (file_exists($timeCardPath) === false) {
            error_log("TimeCard error: Could not locate requested time card data. The {$timeCardPath} time card does not exist.");
            return array();
        }
        return json_decode(file_get_contents($timeCardPath), true);
    }

    private function setTimeCardData(string $timeCardName = '', array $timeCardData = array()): bool
    {
        $timeCardPath = $timeCardName !== '' ? $this->getTimeCardPath($timeCardName) : $this->getCurrentTimeCardPath();
        if (file_exists($timeCardPath) === false) {
            error_log("TimeCard error: Could not set time card data. The {$timeCardPath} time card does not exist.");
            return false;
        }
        return (empty(file_put_contents($timeCardPath, json_encode($timeCardData))) === true ? false : true);
    }

    public function getTimeCardPath(string $timeCardName)
    {
        return $this->jsonDirPath . '/' . $timeCardName . '.json';
    }

    public function calculateTime(int $timeIn, int $timeOut, string $format = 'seconds'): string
    {
        $secondLength = '1';
        $minuteLength = bcmul($secondLength, '60', 2);
        $hourLength = bcmul($minuteLength, '60', 2);
        $dayLength = bcmul($hourLength, 24, 2);
        $secondsPassed = $timeOut - $timeIn;
        switch ($format) {
            case 'days':
                return bcdiv($secondsPassed, $dayLength, 2);//$secondsPassed / $dayLength;
            case 'hours':
                return bcdiv($secondsPassed, $hourLength, 2);
            case 'minutes':
                return bcdiv($secondsPassed, $minuteLength, 2);
            case 'mSeconds':
                return bcmul($secondsPassed, 1000, 2);
            default: // seconds
                return bcsub($timeOut, $timeIn, 2);
        }
    }

    public function calculateTimeCard(string $format = 'seconds', string $timeCardName = ''): string
    {
        $timeCardName = $timeCardName !== '' ? $timeCardName : $this->getCurrentTimeCardName();
        //
        $punches = array_chunk($this->getTimeCardData($timeCardName), 2);
        $hoursWorked = '0.00';
        $minutesWorked = '0.00';
        $secondsWorked = '0.00';
        foreach ($punches as $times) {
            if (count($times) !== 2) {
                continue;
            }
            $hoursWorked = bcadd($hoursWorked, $this->calculateTime($times[0], $times[1], 'hours'), 2);
            $minutesWorked = bcadd($minutesWorked, $this->calculateTime($times[0], $times[1], 'minutes'), 2);
            $secondsWorked = bcadd($secondsWorked, $this->calculateTime($times[0], $times[1], 'seconds'), 2);
        }
        switch ($format) {
            case 'hours':
                return $hoursWorked;
            case 'minutes':
                return $minutesWorked;
            default:
                return $secondsWorked;
        }
    }

    /**
     * Get stored time card names. If no options are set, the names of all stored time
     * cards will be returned.
     * @param array $options If set, this method will return either a range of time cards, or a set
     * of specified time cards depending on what options are set.
     * @return array Array of time card names.
     */
    public function getTimeCardNames($options = array()): array
    {
        $timeCardNames = array();
        foreach ($this->getTimeCardPaths() as $timeCardPath) {
            array_push($timeCardNames, str_replace(array($this->jsonDirPath . '/', '.json'), '', $timeCardPath));
        }
        if (empty($options === false)) {
            $specifiedTimeCardNames = array();
            foreach ($timeCardNames as $timeCardName) {
                // If range is set, make sure to exclude any time cards names that come before or after the specified ranges.
                if (isset($options[self::OPTION_RANGE]) === true && is_array($options[self::OPTION_RANGE]) === true && count($options[self::OPTION_RANGE]) === 2) {
                    if ($timeCardName < $options[self::OPTION_RANGE][0] || $timeCardName > $options[self::OPTION_RANGE][1]) {
                        continue;
                    }
                }
                // If selected is set, make sure to exclude any time card names that do not match one of the selected time card names.
                if (isset($options[self::OPTION_SELECTED]) && is_array($options[self::OPTION_SELECTED])) {
                    if (in_array($timeCardName, $options[self::OPTION_SELECTED], true) === false) {
                        continue;
                    }
                }
                array_push($specifiedTimeCardNames, $timeCardName);
            }
            return $specifiedTimeCardNames;
        }
        return $timeCardNames;
    }

    public function getTimeCardPaths(): array
    {
        return glob($this->jsonDirPath . '/*.json');
    }
}
