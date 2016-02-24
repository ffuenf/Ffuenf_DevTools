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

class Ffuenf_DevTools_Test_Block_Page_Html_Head extends EcomDev_PHPUnit_Test_Case
{
    /**
    * Prepares the environment before running a test.
    */
    protected function setUp()
    {
        @session_start();
        parent::setUp();
    }

    /**
     * @return Ffuenf_DevTools_Block_Page_Html_Head
     */
    protected function getBlock()
    {
        $layout = Mage::getSingleton('core/layout');
        return $layout->createBlock('page/html_head');
    }

    /**
     * Returns the block with two items
     *
     * @return Ffuenf_DevTools_Block_Page_Html_Head
     */
    protected function getBlockWithItems()
    {
        $block = $this->getBlock();
        $block->addCss('A');
        $block->addCss('B');
        $block->addCss('C');
        $block->addCss('D');
        return $block;
    }

    public function testCreateBlock()
    {
        $block = $this->getBlock();
        $this->assertInstanceOf('Ffuenf_DevTools_Block_Page_Html_Head', $block);
    }

    /**
     * @depends testCreateBlock
     */
    public function testStandard()
    {
        $block = $this->getBlockWithItems();
        $this->assertEquals('skin_css/A,skin_css/B,skin_css/C,skin_css/D', implode(',', array_keys($block->getItems())) );
    }

    /**
     * @depends testStandard
     */
    public function testInsertBetweenAfter()
    {
        $block = $this->getBlockWithItems();
        $block->addCss('test1', '', 'A');
        $this->assertEquals('skin_css/A,skin_css/test1,skin_css/B,skin_css/C,skin_css/D', implode(',', array_keys($block->getItems())) );
        $block->addCss('test2', '', 'D');
        $this->assertEquals('skin_css/A,skin_css/test1,skin_css/B,skin_css/C,skin_css/D,skin_css/test2', implode(',', array_keys($block->getItems())) );
    }

    /**
     * @depends testStandard
     */
    public function testInsertBetweenBefore()
    {
        $block = $this->getBlockWithItems();
        $block->addCss('test1', '', 'B', true);
        $this->assertEquals('skin_css/A,skin_css/test1,skin_css/B,skin_css/C,skin_css/D', implode(',', array_keys($block->getItems())) );
        $block->addCss('test2', '', 'D', true);
        $this->assertEquals('skin_css/A,skin_css/test1,skin_css/B,skin_css/C,skin_css/test2,skin_css/D', implode(',', array_keys($block->getItems())) );
    }

    /**
     * @depends testStandard
     */
    public function testInsertLast()
    {
        $block = $this->getBlockWithItems();
        $block->addCss('last', '', '*', false);
        $this->assertEquals('skin_css/A,skin_css/B,skin_css/C,skin_css/D,skin_css/last', implode(',', array_keys($block->getItems())) );
    }

    /**
     * @depends testStandard
     */
    public function testInsertFirst()
    {
        $block = $this->getBlockWithItems();
        $block->addCss('first', '', '*', true);
        $this->assertEquals('skin_css/first,skin_css/A,skin_css/B,skin_css/C,skin_css/D', implode(',', array_keys($block->getItems())) );
    }

    /**
     * @test
     * @depends testStandard
     */
    public function addCssIe()
    {
        $block = $this->getBlock();
        $block->addCssIe('second');
        $block->addCssIe('first', '', 'second', true);
        $this->assertEquals('skin_css/first,skin_css/second', implode(',', array_keys($block->getItems())) );
    }

    /**
     * @test
     * @depends testStandard
     */
    public function addJs()
    {
        $block = $this->getBlock();
        $block->addJs('second');
        $block->addJs('first', '', 'second', true);
        $this->assertEquals('js/first,js/second', implode(',', array_keys($block->getItems())) );
    }

    /**
     * @test
     * @depends testStandard
     */
    public function addJsIe()
    {
        $block = $this->getBlock();
        $block->addJsIe('second');
        $block->addJsIe('first', '', 'second', true);
        $this->assertEquals('js/first,js/second', implode(',', array_keys($block->getItems())) );
    }
}
