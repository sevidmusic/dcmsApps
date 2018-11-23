<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 11/21/18
 * Time: 8:25 PM
 */

namespace Apps\Earnings\classes;


class TimeCardCalculator
{
    public const OPTION_RANGE = 'range';
    public const OPTION_SELECTED = 'selected';
    public const FORMAT_HOURS = 'hours';
    public const FORMAT_MINUTES = 'minutes';
    public const FORMAT_SECONDS = 'seconds';
    private const ZERO = '0.00';
    private const SCALE = 2;

    /**
     * @var TimeCard Instance of a TimeCard object.
     */
    private $timeCard;

    /**
     * TimeCardCalculator constructor.
     * @param $timeCard
     */
    public function __construct(TimeCard $timeCard)
    {
        $this->timeCard = $timeCard;
    }

    /**
     * Calculate the time worked for all, selected, or a range of time cards.
     * @param \Apps\Earnings\classes\TimeCard $timeCard
     * @param string $format (Optional) The format to return the time in. Options are 'second', 'minutes', or 'hours'.
     *                       Default is 'hours'
     * @param array $options (Optional) Array of options. Note: If not set, then all time cards will be included
     *                       in the calculations.
     *
     *                       Valid option indexes are as follows:
     *
     *                       range : An array whose first item ([0]) is the time card to start from, and whose second
     *                               item ([1]) is the time card to end on.
     *                               e.g. $options = array('11122018','11242018'); This would calculate time
     *                               worked for the time cards from 11/12/2018 to 11/24/2018.
     *
     *                       selected : An array of time card names to include in the calculations. If set, any time cards
     *                                  that do not match one of the name in this array will not be included in the calculation.
     * @return string The time worked as a string.
     */
    function calculateTimeWorked(string $format = self::FORMAT_HOURS, array $options = array())
    {
        $timeWorked = self::ZERO;
        foreach ($this->timeCard->getTimeCardNames() as $timeCardName) {
            // if range is set, make sure to exclude any time cards that come before or after the specified ranges.
            if (isset($options[self::OPTION_RANGE]) === true && is_array($options[self::OPTION_RANGE]) === true && count($options[self::OPTION_RANGE]) === 2) {
                if ($timeCardName < $options[self::OPTION_RANGE][0] || $timeCardName > $options[self::OPTION_RANGE][1]) {
                    continue;
                }
            }
            // If selected is set, and timeCardName does not match one of the selected time card names, continue without calculating...
            if (isset($options[self::OPTION_SELECTED]) && is_array($options[self::OPTION_SELECTED])) {
                if (in_array($timeCardName, $options[self::OPTION_SELECTED], true) === false) {
                    continue;
                }
            }
            // If $timeCardName mathches current time card name, account for odd punches so any time worked since last in pucnh will be accounted for in the event that a final punch out has not occurred.
            if ($timeCardName === $this->timeCard->getCurrentTimeCardName()) {
                $timeWorked = bcadd($timeWorked, $this->calculateTimeCard($timeCardName, $format, true), self::SCALE);
                continue;
            }
            $timeWorked = bcadd($timeWorked, $this->calculateTimeCard($timeCardName, $format, false), self::SCALE);
        }
        return $timeWorked;
    }

    /**
     * Calculate total time worked for a specified time card.
     * @param string $timeCardName The name of the time card to calculate.
     * @param string $format (Optional) The format to return the time in. Options: hours, minutes, seconds;
     * @param bool $accountForOddPunches If set to true, and there are an odd number of punches, the last punch will be considered an in punch, and the time since the last punch will be included in the calculations.
     * @return string The amount of time worked for the specified time card.
     */
    public function calculateTimeCard(string $timeCardName, string $format = self::FORMAT_HOURS, bool $accountForOddPunches = false): string
    {
        $punches = array_chunk($this->timeCard->getTimeCardData($timeCardName), 2);
        $timeWorked = self::ZERO;
        foreach ($punches as $times) {
            if (count($times) !== 2) {
                continue;
            }
            $timeWorked = bcadd($timeWorked, $this->calculateTime($times[0], $times[1], $format), self::SCALE);
        }
        if ($accountForOddPunches === true) {
            $punches = $this->timeCard->getTimeCardData($timeCardName);
            // If uneven punches, then account for time worked between "NOW" and last punch since last punch will be last punch in, this will let $hoursWorked accuratley reflect in out punches from today including time worked since last punch in where no punch out...
            if (count($punches) % 2 !== 0) {
                $lastPunch = array_pop($punches);
                $timeWorked = bcadd($timeWorked, $this->calculateTime($lastPunch, $this->timeCard->getCurrentTimestamp(), $format), self::SCALE);
            }
        }
        return $timeWorked;
    }

    /**
     * Calculate the amount of time that has passed between two timestamps.
     * @param int $startTime The start time.
     * @param int $endTime The end time.
     * @param string $format The format to return the time in. Use the FORMAT_* constants.
     * @return string The amount of time that passed between the specified times.
     */
    public function calculateTime(int $startTime, int $endTime, string $format = self::FORMAT_HOURS): string
    {
        $secondLength = '1';
        $minuteLength = bcmul($secondLength, '60', self::SCALE);
        $hourLength = bcmul($minuteLength, '60', self::SCALE);
        $secondsPassed = $endTime - $startTime;
        switch ($format) {
            case self::FORMAT_SECONDS:
                return bcsub($endTime, $startTime, self::SCALE);
            case self::FORMAT_MINUTES:
                return bcdiv($secondsPassed, $minuteLength, self::SCALE);
            default: // hours
                return bcdiv($secondsPassed, $hourLength, self::SCALE);

        }
    }
}
