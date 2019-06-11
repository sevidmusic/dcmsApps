<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 2018-12-31
 * Time: 00:34
 */

namespace Apps\RoleManager\classes;


use DarlingCms\abstractions\userInterface\AjaxUI;

class RoleManagerUI extends AjaxUI
{
    protected $defaultView = 'manageRoles';
    /**
     * AjaxUi constructor.
     * @param string $appName Name of the app the user interface belongs to.
     */
    public function __construct()
    {
        parent::__construct('RoleManager', 'RoleManagerView');
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
        $output = '<div id="RoleManager" class="dcms-admin-panel dcms-admin-panel-pos4 dcms-make-draggable">';
        $output .= '<div id="RoleManagerHandle" class="dcms-drag-handle">Click here to move...</div>';
        $output .= '<div id="RoleManagerViewsMenu">' . implode('', $this->getViewLinks()) . '</div>';
        $output .= '<div id="RoleManagerView">' . $this->getCurrentViewHtml() . '</div>';
        $output .= '</div>';
        return $output;
    }

}
