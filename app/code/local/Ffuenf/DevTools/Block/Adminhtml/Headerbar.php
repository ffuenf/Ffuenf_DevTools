<?php
/**
 * Ffuenf_DevTools extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category   Ffuenf
 * @package    Ffuenf_DevTools
 * @author     Achim Rosenhagen <a.rosenhagen@ffuenf.de>
 * @copyright  Copyright (c) 2015 ffuenf (http://www.ffuenf.de)
 * @license    http://opensource.org/licenses/mit-license.php MIT License
 */

class Ffuenf_DevTools_Block_Adminhtml_Headerbar extends Mage_Core_Block_Template
{
    /**
     * @return string
     */
    protected function _toHtml()
    {
        /** @var Ffuenf_DevTools_Helper_Data $helper */
        $helper = $this->helper('ffuenf_devtools');
        return '<div id="headerbar" class="' . $helper->getClassName() . '"><div class="gitinfo"><span class="branch" title="branch">' . $helper->getBranch() . '</span> / <span class="commit" title="commit">' . $helper->getCommit() . '</span> / <span class="tag" title="tag">' . $helper->getTag() . '</span></div></div>';
    }
}
