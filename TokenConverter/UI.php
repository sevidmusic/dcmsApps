<?php


namespace Apps\TokenConverter;


use DarlingCms\abstractions\userInterface\AjaxUi;
use DarlingCms\interfaces\userInterface\IUserInterface;

class UI extends AjaxUi implements IUserInterface
{

    protected $defaultView = 'tokensToCash';

    /**
     * UI constructor.
     */
    public function __construct()
    {
        parent::__construct('TokenConverter', 'TokenConverterView');
    }

    protected function getViewsDirName(): string
    {
        return 'ajax';
    }

    /**
     * Gets the user interface.
     * @return string The user interface.
     */
    public function getUserInterface(): string
    {
        $output = '<div id="TokenConverter" class="dcms-admin-panel dcms-admin-panel-pos3 dcms-make-draggable">';
        $output .= '<div id="TokenConverterHandle" class="dcms-drag-handle">Click here to move...</div>';
        //$output .= '<div id="TokenConverterViewsMenu">' . implode('', $this->getViewLinks()) . '</div>';
        $output .= '<div id="TokenConverterView">' . $this->getCurrentViewHtml() . '</div>';
        //$output .= '<div id="TokenConversionView"></div>';
        $output .= '</div>';
        return $output;
    }

}
