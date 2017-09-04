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

class Ffuenf_DevTools_Block_Customer_Account_Navigation extends Mage_Customer_Block_Account_Navigation
{
    /**
     * Set the original module name avoid breaking translations
     */
    public function __construct()
    {
        parent::__construct();
        $this->setModuleName('Mage_Customer');
    }

    /**
     * Remove a link
     *
     * @param string $name Name of the link
     * @return \Ffuenf_DevTools_Block_Customer_Account_Navigation
     */
    public function removeLink($name)
    {
        unset($this->_links[$name]);
        return $this;
    }

    /**
     * Remove a link by name
     *
     * @param $name Name of the link
     * @return \Ffuenf_DevTools_Block_Customer_Account_Navigation
     */
    public function removeLinkByName($name)
    {
        foreach ($this->_links as $k => $v) {
            if ($v->getName() == $name) {
                unset($this->_links[$k]);
            }
        }
        return $this;
    }
}
