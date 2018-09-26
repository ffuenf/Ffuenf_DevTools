<?php
/**
 * Ffuenf_DevTools extension.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category   Ffuenf
 *
 * @author     Achim Rosenhagen <a.rosenhagen@ffuenf.de>
 * @copyright  Copyright (c) 2018 ffuenf (http://www.ffuenf.de)
 * @license    http://opensource.org/licenses/mit-license.php MIT License
 */

class Ffuenf_DevTools_Block_Adminhtml_System_Config_Date extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /*
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $element->setFormat(Varien_Date::DATE_INTERNAL_FORMAT);
        $element->setImage($this->getSkinUrl('images/grid-cal.gif'));
        return parent::render($element);
    }
}
