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
 * @copyright  Copyright (c) 2017 ffuenf (http://www.ffuenf.de)
 * @license    http://opensource.org/licenses/mit-license.php MIT License
 */

class Ffuenf_DevTools_Model_Observer
{
    /**
     * @fire adminhtml_controller_action_predispatch_start
     */
    public function overrideTheme()
    {
        $theme = trim(Mage::getStoreConfig('design/admin/theme'));
        if (empty($theme)) {
            return;
        }
        Mage::getDesign()->setArea('adminhtml')->setTheme($theme);
    }

    /**
     * @fire adminhtml_block_html_before
     *
     * @param Varien_Event_Observer $observer
     */
    public function setBodyClass(Varien_Event_Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();
        if ($block instanceof Mage_Adminhtml_Block_Page) {
            $block->addBodyClass('headerbar-added');
        }
    }
}
