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
    public const DEFAULT_VIEW = 'AllApps';
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
        $this->view = isset($selectedView) ? $selectedView : self::DEFAULT_VIEW;
    }


    /**
     * Gets the user interface.
     * @return string The user interface.
     */
    public function getUserInterface(): string
    {
        ob_start();
        include_once $this->getViewsDirPath() . $this->view . '.php';
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
        $html = "<select {$selectState} onchange=\"return AjaxRouterRequest('AppManager','updateAppState','am-message','GET',undefined,'updateAppState='+this.value+'&appName={$appName}', 'ajax','AMShowMessages')\" name=\"am_appEnabled_{$appName}\" class=\"dcms-button am-app-enabled-select\">";
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
            $html .= '<li>' . $theme . '</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    public function getJsLibraryList(string $appName): string
    {
        $html = '<ul class="dcms-descriptive-text">';
        foreach ($this->appInfo->getJsLibraries($appName) as $jsLibrary) {
            $html .= '<li>' . $jsLibrary . '</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    public function getSimpleAdvancedLinks(): string
    {
        $html = '<div class="am-sa-link-container">';
        if ($this->displayAdvancedInfo() === false) {
            $html .= '<a class="dcms-link am-advanced-link" href="?advancedInfo&amp;appManagerView=' . $this->view . '">Advanced...</a>';
        }
        if ($this->displayAdvancedInfo() === true) {
            $html .= '<a class="dcms-link am-simple-link" href="?simpleInfo&amp;appManagerView=' . $this->view . '">Simple...</a>';
        }
        $html .= '</div>';
        return $html;
    }

    public function getAppLogoImg(string $appName): string
    {
        return "<img class='dcms-float-left am-app-logo-img' src='{$this->appInfo->getDemoImgPath($appName)}' alt='App Logo'>";
    }

    public function displayAdvancedInfo(): bool
    {
        return $this->displayAdvancedInfo;
    }

    public function getViewLinks(): string
    {
        $links = array();
        foreach (scandir(str_replace('classes', 'views', __DIR__)) as $view) {
            if ($view !== '.' && $view !== '..' && $view !== 'subViews') {
                $viewName = str_replace('.php', '', $view);
                array_push($links, '<a class="dcms-link app-manager-view-link" onclick="return AjaxRouterRequest(\'AppManager\',\'' . trim($viewName) . '\',\'AppManagerCurrentView\',\'GET\',undefined,\'appManagerView=' . trim($viewName) . '&ajaxRequest=true\',\'views\')" href="index.php?appManagerView=' . trim($viewName) . ($this->displayAdvancedInfo() === true ? '&amp;advancedInfo' : '') . '">' . $this->convertFromCamelCase(trim($viewName)) . '</a>');
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
                <div id="am-message-container" style="" class="am-msg-container">
                    <p>App Manager Message: <span id="am-message"></span></p>
                </div>
                ' . $this->getSimpleAdvancedLinks() .
            '</div>';
        return $html;
    }

    public function getAbout(string $appName): string
    {
        return '
                <div class="am-about-container">
                    <p class="am-readme dcms-clearfix">
                        ' . $this->getAppLogoImg($appName) . $this->appInfo->getReadme($appName) . '
                    </p>
                    <div class="dcms-clear-float"></div>
                </div>
                ';
    }

    public function convertFromCamelCase(string $string): string
    {
        // Both REGEX solutions found on stackoverflow. @see https://stackoverflow.com/questions/4519739/split-camelcase-word-into-words-with-php-preg-match-regular-expression
        $pattern = '/(?(?<=[a-z])(?=[A-Z])|(?<=[A-Z])(?=[A-Z][a-z]))/x'; // ridgerunner's answer | BETTER: This pattern can accommodate even malformed camel case like camelCASEString
        //$pattern = '/((?:^|[A-Z])[a-z]+)/'; // codaddict's answer | approved answer | WARNING: This pattern does not handle malformed camel case strings like camelCASEString, kept for reference.
        $words = preg_split($pattern, $string);
        return ucwords(implode(' ', $words));
    }
}
