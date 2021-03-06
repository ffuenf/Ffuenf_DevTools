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

/**
 * @see Ffuenf_DevTools_Helper_Data
 *
 * @loadSharedFixture shared
 */

class Ffuenf_DevTools_Test_Helper_Data extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @var Ffuenf_DevTools_Helper_Data
     */
    protected $_helper;

    public function setUp()
    {
        $this->_helper = new Ffuenf_DevTools_Helper_Data();
    }

    public function tearDown()
    {
        $this->_helper = null;
    }

    /**
     * Tests whether extension is active.
     *
     * @test
     * @covers Ffuenf_DevTools_Helper_Data::isExtensionActive
     */
    public function testIsExtensionActive()
    {
        $this->assertTrue(
            $this->_helper->isExtensionActive(),
            'Extension is not active please check config'
        );
    }

    /**
     * Tests whether extension logging is active.
     *
     * @test
     * @covers Ffuenf_DevTools_Helper_Data::isLogActive
     */
    public function testIsLogActive()
    {
        $this->assertTrue(
            $this->_helper->isLogActive(),
            'System logging is not active please check config'
        );
    }

    /**
     * Tests whether extension profile logging is active.
     *
     * @test
     * @covers Ffuenf_DevTools_Helper_Data::isLogProfileActive
     */
    public function testIsLogProfileActive()
    {
        $this->assertTrue(
            $this->_helper->isLogProfileActive(),
            'Profile logging is not active please check config'
        );
    }

    /**
     * Tests whether extension exception logging is active.
     *
     * @test
     * @covers Ffuenf_DevTools_Helper_Data::isLogExceptionActive
     */
    public function testIsLogExceptionActive()
    {
        $this->assertTrue(
            $this->_helper->isLogExceptionActive(),
            'Exception logging is not active please check config'
        );
    }

    /**
     * Tests whether magerun is available.
     *
     * @test
     * @loadFixture
     * @covers Ffuenf_DevTools_Helper_Data::isMagerunAvailable
     */
    public function testIsMagerunAvailable()
    {
        $this->assertTrue(
            $this->_helper->isMagerunAvailable(),
            'No valid magerun found'
        );
    }

    /**
     * Tests whether dump_database shell script is available.
     *
     * @test
     * @loadFixture
     * @covers Ffuenf_DevTools_Helper_Data::getDatabaseDumpScriptPath
     */
    public function testGetDatabaseDumpScriptPath()
    {
        $this->assertEquals(
            $this->_helper->getDatabaseDumpScriptPath(),
            '../../shared/dump_database.sh',
            'database_dump script path is not set please check config'
        );
    }

    /**
     * Tests whether extension uses the old-style admin routing (not compatible with SUPEE-6788).
     *
     * @test
     */
    public function testGetOldAdminRouting()
    {
        $routers = Mage::getConfig()->getNode('admin/routers');
        $offendingExtensions = array();
        foreach ($routers[0] as $router) {
            $name = $router->args->module;
            if ($name != 'Mage_Adminhtml') {
                $offendingExtensions[] = $router->args->module;
            }
        }
        $this->assertEquals(
            count($offendingExtensions),
            0,
            'This extension uses old-style admin routing which is not compatible with SUPEE-6788 / Magento 1.9.2.2+'
        );
    }
}

