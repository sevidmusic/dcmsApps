<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 10/31/18
 * Time: 12:46 PM
 */

namespace Apps\AppManager\classes;

use DarlingCms\interfaces\userInterface\IUserInterface;

class AppManagerUI implements IUserInterface
{
    private $appInfo;

    private $displayAdvancedInfo = false;

    private $view;

    /**
     * AppManagerUI constructor.
     */
    public function __construct(AppInfo $appInfo)
    {
        $this->appInfo = $appInfo;
        $this->displayAdvancedInfo = (filter_input(INPUT_GET, 'advancedInfo') === '' ? true : false);
        $selectedView = filter_input(INPUT_GET, 'appManagerView');
        $this->view = isset($selectedView) ? $selectedView : 'view1';
    }


    /**
     * Gets the user interface.
     * @return string The user interface.
     */
    public function getUserInterface(): string
    {
        ob_start();
        include_once $this->getViewsDirPath() . $this->view . '.php'; // this should be the return value for getUserInterface()...
        $viewHtml = ob_get_clean();
        return $viewHtml;
    }

    /**
     * Returns the html for an on/off select form element for a specified app.
     * @param string $appName The name of the app to generate a on/off select element for.
     * @return string The string of html for the select element.
     */
    public function getAppOnOffSelect(string $appName): string
    {
        $selectState = ($appName === 'AppManager' ? 'disabled' : '');
        $html = "<select {$selectState} onchange=\"amUpdateAppState(this.value, '{$appName}')\" name=\"am_appEnabled_{$appName}\" class=\"dcms-simple-select dcms-hover am-app-enabled-select\">";
        switch ($this->appInfo->isEnabled($appName)) {
            case true:
                $html .= '<option selected>On</option><option>Off</option>';
                break;
            case false:
                $html .= '<option>On</option><option selected>Off</option>';
                break;
        }
        $html .= '</select>';
        return $html;
    }

    public function getThemeList(string $appName): string
    {
        $html = '<ul class="dcms-descriptive-text">';
        foreach ($this->appInfo->getThemes($appName) as $theme) {
            echo '<li>' . $theme . '</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    public function getJsLibraryList(string $appName): string
    {
        $html = '<ul class="dcms-descriptive-text">';
        foreach ($this->appInfo->getJsLibraries($appName) as $jsLibrary) {
            echo '<li>' . $jsLibrary . '</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    public function getSimpleAdvancedLinks(): string
    {
        $html = '<div class="am-sa-link-container">';
        if ($this->displayAdvancedInfo() === false) {
            $html .= '<a class="dcms-extra-large-link am-advanced-link" href="?advancedInfo&amp;appManagerView=' . $this->view . '">Advanced...</a>';
        }
        if ($this->displayAdvancedInfo() === true) {
            $html .= '<a class="dcms-extra-large-link am-simple-link" href="?simpleInfo&amp;appManagerView=' . $this->view . '">Simple...</a>';
        }
        $html .= '</div>';
        return $html;
    }

    public function getAppLogoImg(string $appName): string
    {
        return "<img class='am-app-logo-img' src='{$this->appInfo->getDemoImgPath($appName)}'>";
    }

    public function displayAdvancedInfo(): bool
    {
        return $this->displayAdvancedInfo;
    }

    public function getViewLinks(): string
    {
        $links = array();
        foreach (scandir(str_replace('classes', 'views', __DIR__)) as $view) {
            if ($view !== '.' && $view !== '..') {
                $viewName = str_replace('.php', '', $view);
                array_push($links, '<a href="index.php?appManagerView=' . $viewName . ($this->displayAdvancedInfo() === true ? '&amp;advancedInfo' : '') . '">' . $viewName . '</a>');
            }
        }
        return '
        <div class="am-view-links-container">
            ' . implode(PHP_EOL, $links) . '
        </div>
        ';
    }

    private function getViewsDirPath(): string
    {
        return str_replace('classes', 'views/', __DIR__);
    }

    public function getToolbar(): string
    {
        $html = '
             <div class="am-toolbar-container">
                <div id="amMsgContainer" style="display:none;" class="am-msg-container">
                    <p>App Manager Message: <span id="amMsg"></span></p>
                </div>
                ' . $this->getSimpleAdvancedLinks() .
            '</div>';
        return $html;
    }

    public function getAbout(string $appName)
    {
        switch ($this->displayAdvancedInfo()) {
            case true:
                echo '
                <div class="dcms-clear-float"></div>
                <div class="dcms-sub-container dcms-float-left dcms-container-border-right dcms-quarter-width dcms-short-container">
                    <h4>About</h4>
                    <div class="am-app-readme-container">
                        ' . $this->appInfo->getReadme($appName) . '
                    </div>
                </div>';
                break;
            default:
                echo '
                    <div class="dcms-sub-container dcms-float-left dcms-container-border-center dcms-full-width">
                    <h4>About</h4>
                    <div class="am-app-readme-container">
                        ' . $this->appInfo->getReadme($appName) . '
                    </div>
                </div>';
                break;
        }
    }
}
