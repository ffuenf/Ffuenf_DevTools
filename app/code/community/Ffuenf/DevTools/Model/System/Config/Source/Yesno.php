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
 * @copyright  Copyright (c) 2016 ffuenf (http://www.ffuenf.de)
 * @license    http://opensource.org/licenses/mit-license.php MIT License
 */

class Ffuenf_DevTools_Model_System_Config_Source_Yesno
{
    public function toOptionArray()
    {
        return array(
            array('value' => 0, 'label' => Mage::helper('ffuenf_devtools')->__('Enabled')),
            array('value' => 1, 'label' => Mage::helper('ffuenf_devtools')->__('Disabled')),
        );
    }
}
