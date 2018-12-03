<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 11/20/18
 * Time: 10:41 PM
 */

namespace Apps\Earnings\classes;


use DarlingCms\interfaces\userInterface\IUserInterface;

class EarningsUI implements IUserInterface
{
    public const EARNINGS_VIEW_VAR_NAME = 'earningsView';

    private $timeCard;

    private $timeCardCalculator;

    private $view = 'Today';

    /**
     * EarningsUI constructor.
     */
    public function __construct(TimeCard $timeCard)
    {
        $this->timeCard = $timeCard;
        /**
         * DEV NOTE:
         * The TimeCardCalculator instance in instantiated within __construct() instead of injected via parameter on
         * purpose. This insures any time card calculations that are done on behalf of the UI are accurate to the
         * TimeCard instance that was injected into this object on instantiation.
         */
        $this->timeCardCalculator = new TimeCardCalculator($this->timeCard); // @ TODO Consider making the EarningsUI and extension of the TimeCardCalculator class since it does perform calculations.
        $this->view = empty(filter_input(INPUT_GET, self::EARNINGS_VIEW_VAR_NAME)) === false ? filter_input(INPUT_GET, self::EARNINGS_VIEW_VAR_NAME) : $this->view;
    }

    /**
     * Gets the user interface.
     * @return string The user interface.
     */
    public function getUserInterface(): string
    {
        $html = $this->getMainContainerStart();
        $html .= $this->getDragHandle();
        $html .= $this->getMainMenu();
        $html .= $this->getEarningsClock();
        $html .= $this->getPunchButton();
        $html .= $this->getCurrentView();
        $html .= $this->getMainContainerEnd();
        return $html;
    }

    private function getViewUrl(string $viewName): string
    {
        if (empty($viewName) === false) {
            return 'http://localhost:8888/DarlingCms/?' . self::EARNINGS_VIEW_VAR_NAME . '=' . $viewName;
        }
        return 'http://localhost:8888/DarlingCms/?' . self::EARNINGS_VIEW_VAR_NAME . '=' . $this->view;
    }

    public function getDragHandle()
    {
        return '<div draggable="true" id="punchDisplayHandle" class="dragHandle">Click here to move</div>';
    }

    public function getMainMenu(): string
    {
        return "<div id=\"mainMenu\" class=\"sticky sticky-menu\">
                <a onclick=\"return AjaxRouterRequest('Earnings','Today','EarningsAjaxOutput','GET',undefined,'" . self::EARNINGS_VIEW_VAR_NAME . "=Today&ajaxRequest=true','views')\" class=\"sticky-menu-link\" href=\"{$this->getViewUrl('Today')}\">Today</a>
                <a onclick=\"return AjaxRouterRequest('Earnings','Earnings','EarningsAjaxOutput','GET',undefined,'" . self::EARNINGS_VIEW_VAR_NAME . "=Earnings&ajaxRequest=true','views')\" class=\"sticky-menu-link\" href=\"{$this->getViewUrl('Earnings')}\">Earnings</a>
                <a onclick=\"return AjaxRouterRequest('Earnings','Punches','EarningsAjaxOutput','GET',undefined,'" . self::EARNINGS_VIEW_VAR_NAME . "=Punches&ajaxRequest=true','views')\" class=\"sticky-menu-link\" href=\"{$this->getViewUrl('Punches')}\">Punches</a>
                <a onclick=\"return AjaxRouterRequest('Earnings','TimeWorked','EarningsAjaxOutput','GET',undefined,'" . self::EARNINGS_VIEW_VAR_NAME . "=TimeWorked&ajaxRequest=true','views')\" class=\"sticky-menu-link\" href=\"{$this->getViewUrl('TimeWorked')}\">Time Worked</a>
                <a onclick=\"return AjaxRouterRequest('Earnings','Invoice','EarningsAjaxOutput','GET',undefined,'" . self::EARNINGS_VIEW_VAR_NAME . "=Invoice&ajaxRequest=true','views')\" class=\"sticky-menu-link\" href=\"{$this->getViewUrl('Invoice')}\">Invoice</a>
                <a onclick=\"return AjaxRouterRequest('Earnings','Dev','EarningsAjaxOutput','GET',undefined,'" . self::EARNINGS_VIEW_VAR_NAME . "=Dev&ajaxRequest=true','views')\" class=\"sticky-menu-link\" href=\"{$this->getViewUrl('Dev')}\">Dev</a>
        </div>";
    }

    public function getEarningsClock()
    {
        return '<p id="earningsClock">' . date('h:i:s', $this->timeCard->getCurrentTimestamp()) . '</p>';
    }

    public function getPunchButton(): string
    {
        return '<button id="punchButton" class="earnings-button" onclick="earningsPunch()">Punch</button>';
    }

    public function getCurrentView(): string
    {
        ob_start();
        include pathinfo(__DIR__, PATHINFO_DIRNAME) . '/views/' . $this->view . '.php';
        return '<div id="EarningsAjaxOutput">' . ob_get_clean() . '</div>';

    }

    public function getCurrentViewName(): string
    {
        return $this->view;

    }

    public function getMainContainerStart(): string
    {
        return '<div id="punchDisplay" class="earnings-punch-display makeDraggable dcms-admin-panel">';
    }

    public function getMainContainerEnd()
    {
        return '</div>'; // close #punchDisplay.
    }

    public static function formatTimeForDisplay(string $hours, string $minutes): string
    {
        // calculate hours for display
        $displayHours = bcadd($hours, 0, 0);
        // calculate minutes for display
        $decimalMinutes = explode('.', $hours);
        $leftOverMinutes = bcmul('.' . $decimalMinutes[1], 60, 2);
        $displayMinutes = bcadd($leftOverMinutes, 0, 0);
        // calculate seconds for display
        $decimalSeconds = explode('.', $minutes);
        $leftOverSeconds = bcmul('.' . $decimalSeconds[1], 60, 2);
        $displaySeconds = bcadd($leftOverSeconds, 0, 0);
        return "{$displayHours} hour" . (bcadd(0, $hours) === '1' ? '' : 's') . " {$displayMinutes} minutes {$displaySeconds} seconds";
    }

    public function getTimeCardRangeSelector()
    {
        include realpath('../views/tools/TimeCardRangeSelector.php');
    }

    /**
     * Returns an array of time card names via the TimeCard::getTimeCardNames() method.
     * Note: If the $_GET['startingTimeCardName'] and $_GET['endingTimeCardName'] vars are set this method will
     * set the range of time card names returned based on those values, otherwise it will base the range of
     * time card names returned based on the oldest and newest time card names, i.e. it will return all
     * time card names.
     * This method is essentially an alias for TimeCard::getTimeCardNames(array(\Apps\Earnings\classes\TimeCardCalculator::OPTION_RANGE => [$startingTimeCardName, $endingTimeCardName])
     * @return array Array of time card names.
     * @see TimeCard::getTimeCardNames()
     */
    public function getTimeCardNames()
    {
        switch ($this->getStartingTimeCardName() < $this->getEndingTimeCardName()) {
            case true:
                return $this->timeCard->getTimeCardNames(array(TimeCard::OPTION_RANGE => [$this->getStartingTimeCardName(), $this->getEndingTimeCardName()]));
            case false:
                return array_reverse($this->timeCard->getTimeCardNames(array(TimeCard::OPTION_RANGE => [$this->getEndingTimeCardName(), $this->getStartingTimeCardName()])));
        }
        return $this->timeCard->getTimeCardNames();
    }

    /**
     * Get the name of the starting time card based on either the $_GET['startingTimeCardName'] var or the
     * name of the oldest time card.
     * @return string The name of the starting time card.
     */
    public function getStartingTimeCardName(): string
    {
        return (!empty(filter_input(INPUT_GET, 'startingTimeCardName')) ? filter_input(INPUT_GET, 'startingTimeCardName') : $this->getOldestTimeCardName());
    }

    /**
     * Returns the name of the newest time card.
     * @return string The name of the newest time card.
     */
    public function getNewestTimeCardName(): string
    {
        $timeCards = $this->timeCard->getTimeCardNames();
        return array_pop($timeCards);
    }

    /**
     * Returns the name of the oldest time card.
     * @return string The name of the oldest time card.
     */
    public function getOldestTimeCardName(): string
    {
        $timeCards = $this->timeCard->getTimeCardNames();
        return array_shift($timeCards);
    }

    /**
     * Get the name of the ending time card based on either the $_GET['endingTimeCardName'] var or the
     * name of the newest time card.
     * @return string The name of the starting time card.
     */
    public function getEndingTimeCardName()
    {
        return (!empty(filter_input(INPUT_GET, 'endingTimeCardName')) ? filter_input(INPUT_GET, 'endingTimeCardName') : $this->getNewestTimeCardName());
    }

    public function formatTimeCardName(string $timeCardName)
    {
        return "{$timeCardName[0]}{$timeCardName[1]}/{$timeCardName[2]}{$timeCardName[3]}/{$timeCardName[4]}{$timeCardName[5]}{$timeCardName[6]}{$timeCardName[7]}";
    }

    /**
     * Calculates time worked for the currently selected range of Time Cards as determined by the
     * getStartingTimeCardName() and getEndingTimeCardName() methods.
     * @param string $format The format to return it in. Options are as follows:
     *                       \Apps\Earnings\classes\TimeCardCalculator::FORMAT_HOURS
     *                       \Apps\Earnings\classes\TimeCardCalculator::FORMAT_MINUTES
     *                       \Apps\Earnings\classes\TimeCardCalculator::FORMAT_SECONDS
     * @return string The time worked between the currently selected range of Time Cards in the specified format.
     * @see TimeCardCalculator::calculateTimeWorked()
     * @see TimeCardCalculator::FORMAT_HOURS
     * @see TimeCardCalculator::FORMAT_MINUTES
     * @see TimeCardCalculator::FORMAT_SECONDS
     */
    public function geTimeWorkedFromSelected(string $format = \Apps\Earnings\classes\TimeCardCalculator::FORMAT_HOURS)
    {
        switch ($this->getStartingTimeCardName() < $this->getEndingTimeCardName()) {
            case true:
                return $this->timeCardCalculator->calculateTimeWorked($format, [\Apps\Earnings\classes\TimeCardCalculator::OPTION_RANGE => [$this->getStartingTimeCardName(), $this->getEndingTimeCardName()]]);
            default:
                return $this->timeCardCalculator->calculateTimeWorked($format, [\Apps\Earnings\classes\TimeCardCalculator::OPTION_RANGE => [$this->getEndingTimeCardName(), $this->getStartingTimeCardName()]]);
        }
    }

}
