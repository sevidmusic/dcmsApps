<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 2019-01-06
 * Time: 08:30
 */

namespace Apps\PermissionsManager\classes;


use DarlingCms\abstractions\userInterface\AjaxUi;

class PermissionManagerUI extends AjaxUi
{
    protected $defaultView = 'managePermissions';
    /**
     * AjaxUi constructor.
     * @param string $appName Name of the app the user interface belongs to.
     */
    public function __construct()
    {
        parent::__construct('PermissionManager', 'PermissionManagerView');
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
        $output = '<div id="PermissionManager" class="dcms-admin-panel dcms-admin-panel-pos4 dcms-make-draggable">';
        $output .= '<div id="PermissionManagerHandle" class="dcms-drag-handle">Click here to move...</div>';
        $output .= '<div id="PermissionManagerViewsMenu">' . implode('', $this->getViewLinks()) . '</div>';
        $output .= '<div id="PermissionManagerView">' . $this->getCurrentViewHtml() . '</div>';
        $output .= '</div>';
        return $output;
    }
}
