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
    private $timeCard;

    private $view = 'Today';

    /**
     * EarningsUI constructor.
     */
    public function __construct(TimeCard $timeCard)
    {
        $this->timeCard = $timeCard;
        $this->view = empty(filter_input(INPUT_GET, 'earningsView')) === false ? filter_input(INPUT_GET, 'earningsView') : $this->view;
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
            return 'http://localhost:8888/DarlingCms/?earningsView=' . $viewName;
        }
        return 'http://localhost:8888/DarlingCms/?earningsView=' . $this->view;
    }

    public function getDragHandle()
    {
        return '<div draggable="true" id="punchDisplayheader" class="dragHandle">Click here to move</div>';
    }

    public function getMainMenu(): string
    {
        return '<div id="mainMenu" class="sticky sticky-menu">
                <a class="sticky-menu-link" href="' . $this->getViewUrl('Today') . '">Today</a>
                <a class="sticky-menu-link" href="' . $this->getViewUrl('Earnings') . '">Earnings</a>
                <a class="sticky-menu-link" href="' . $this->getViewUrl('Punches') . '">Punches</a>
                <a class="sticky-menu-link" href="' . $this->getViewUrl('TimeWorked') . '">Time Worked</a>
                <a class="sticky-menu-link" href="' . $this->getViewUrl('Dev') . '">Dev</a>
        </div>';
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
        return ob_get_clean();

    }

    public function getMainContainerStart()
    {
        return '<div id="punchDisplay" class="earnings-punch-display">';
    }

    public function getMainContainerEnd()
    {
        return '</div>';
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
}
