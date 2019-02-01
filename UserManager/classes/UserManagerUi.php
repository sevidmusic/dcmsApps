<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 2018-12-31
 * Time: 00:34
 */

namespace Apps\UserManager\classes;


use DarlingCms\abstractions\userInterface\AjaxUi;

class UserManagerUi extends AjaxUi
{
    protected $defaultView = 'manageUsers';

    /**
     * AjaxUi constructor.
     * @param string $appName Name of the app the user interface belongs to.
     */
    public function __construct()
    {
        parent::__construct('UserManager', 'UserManagerView');
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
        $output = '<div id="UserManager" class="dcms-admin-panel dcms-admin-panel-pos1 dcms-make-draggable">';
        $output .= '<div id="UserManagerHandle" class="dcms-drag-handle">Click here to move...</div>';
        $output .= '<div id="UserManagerViewsMenu">' . implode('', $this->getViewLinks()) . '</div>';
        $output .= '<div id="UserManagerView">' . $this->getCurrentViewHtml() . '</div>';
        $output .= '</div>';
        return $output;
    }

}
