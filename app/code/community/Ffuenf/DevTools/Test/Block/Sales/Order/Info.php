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

class Ffuenf_DevTools_Test_Block_Sales_Order_Info extends EcomDev_PHPUnit_Test_Case
{

    public function setUp()
    {
        parent::setUp();
        $mockSession = $this->getModelMockBuilder('customer/session')
            ->disableOriginalConstructor()
            ->getMock();
        $this->replaceByMock('singleton', 'customer/session', $mockSession);
        /* @var $mockSession PHPUnit_Framework_MockObject_MockObject Stub */
        $mockSession = Mage::getSingleton('customer/session');
        $mockSession->expects($this->atLeastOnce())
            ->method('authenticate')
            ->willReturn(true);
        $mockSession->expects($this->atLeastOnce())
            ->method('getCustomer')
            ->willReturn(Mage::getModel('customer/customer')->setData('email', 'test@test.com'));
        
        $infoBlock = $this->getBlockMock('payment/info');
        $paymentHelperMock = $this->getHelperMock('payment/data', array('getInfoBlock'));
        $paymentHelperMock->expects($this->any())
            ->method('getInfoBlock')
            ->will($this->returnValue($infoBlock));
        $this->replaceByMock('helper', 'payment', $paymentHelperMock);
        $payment = $this->getModelMock('sales/order_payment');
        $orderMock = $this->getModelMock('sales/order', array('hasShipments', 'hasCreditmemos', 'getPayment'));
        $orderMock->expects($this->any())->method('getPayment')->will($this->returnValue($payment));
        Mage::register('current_order', $orderMock);
    }

    public function tearDown()
    {
        Mage::unregister('current_order');
        parent::tearDown();
    }

    /**
     * @return Ffuenf_DevTools_Block_Sales_Order_Info
     */
    protected function getBlock()
    {
        $layout = Mage::getSingleton('core/layout');
        return $layout->createBlock('sales/order_info');
    }

    public function testCreateBlock()
    {
        $block = Mage::app()->getLayout()->createBlock('sales/order_info');
        $this->assertInstanceOf('Ffuenf_DevTools_Block_Sales_Order_Info', $block);
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
