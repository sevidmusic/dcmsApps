<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 2018-12-31
 * Time: 00:34
 */

namespace Apps\ActionManager\classes;


use DarlingCms\abstractions\userInterface\AjaxUi;

class ActionManagerUi extends AjaxUi
{
    /**
     * AjaxUi constructor.
     * @param string $appName Name of the app the user interface belongs to.
     */
    public function __construct()
    {
        parent::__construct('ActionManager', 'ActionManagerView');
    }

    protected function getViewsDirName(): string
    {
        return 'views';
    }

    /**
     * Gets the user interface.
     * @return string The user interface.
     */
    public function getUserInterface(): string
    {
        $output = '<div id="ActionManager" class="dcms-admin-panel dcms-admin-panel-pos4 dcms-make-draggable">';
        $output .= '<div id="ActionManagerHandle" class="dcms-drag-handle">Click here to move...</div>';
        $output .= '<div id="ActionManagerViewsMenu">' . implode('', $this->getViewLinks()) . '</div>';
        $output .= '<div id="ActionManagerView">' . $this->getCurrentViewHtml() . '</div>';
        $output .= '</div>';
        return $output;
    }

}
