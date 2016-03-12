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

class Ffuenf_DevTools_Test_Block_Customer_Account_Navigation extends EcomDev_PHPUnit_Test_Case
{
    /**
    * Prepares the environment before running a test.
    */
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * @return Ffuenf_DevTools_Block_Customer_Account_Navigation
     */
    protected function getBlock()
    {
        $layout = Mage::getSingleton('core/layout');
        return $layout->createBlock('customer/account_navigation');
    }

    public function testCreateBlock()
    {
        $block = Mage::app()->getLayout()->createBlock('customer/account_navigation');
        $this->assertInstanceOf('Ffuenf_DevTools_Block_Customer_Account_Navigation', $block);
    }

    /**
     * @test
     */
    public function removeLink()
    {
        $block = $this->getBlock();
        $block->addLink('removeme', '/foo', 'Remove Me');
        $block->addLink('some', '/bar', 'Some Link');
        $this->assertArrayHasKey('removeme', $block->getLinks());
        $this->assertArrayHasKey('some', $block->getLinks());
        $block->removeLink('removeme');
        $this->assertArrayNotHasKey('removeme', $block->getLinks());
        $this->assertArrayHasKey('some', $block->getLinks());
    }
}
