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

/**
* @see Ffuenf_DevTools_Helper_Data
*
* @loadSharedFixture shared
*/

class Ffuenf_DevTools_Test_Helper_Data extends EcomDev_PHPUnit_Test_Case
{

    /**
    * Tests whether extension is active
    *
    * @test
    * @covers Ffuenf_DevTools_Helper_Data::isExtensionActive
    */
    public function testIsExtensionActive()
    {
        $this->assertTrue(
            Mage::helper('ffuenf_devtools')->isExtensionActive(),
            'Extension is not active please check config'
        );
    }

    /**
     * Tests whether magerun is available
     *
     * @test
     * @loadFixture
     * @covers Ffuenf_DevTools_Helper_Data::isMagerunAvailable
     */
    public function testIsMagerunAvailable()
    {
        $this->assertTrue(
            Mage::helper('ffuenf_devtools')->isMagerunAvailable(),
            'No valid magerun found'
        );
    }

    /**
     * Tests whether dump_database shell script is available
     *
     * @test
     * @loadFixture
     * @covers Ffuenf_DevTools_Helper_Data::getDatabaseDumpScriptPath
     */
    public function testGetDatabaseDumpScriptPath()
    {
        $this->assertEquals(
            Mage::helper('ffuenf_devtools')->getDatabaseDumpScriptPath(),
            '../../shared/dump_database.sh',
            'database_dump script path is not set please check config'
        );
    }
}