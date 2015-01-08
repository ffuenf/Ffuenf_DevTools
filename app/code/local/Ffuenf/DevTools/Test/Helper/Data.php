<?php

/**
* Test for class Ffuenf_Pagespeed_Helper_Data
*
* @category    Ffuenf
* @package     Ffuenf_DevTools
* @author      Achim Rosenhagen <a.rosenhagen@ffuenf.de>
* @copyright   Copyright (c) 2015 ffuenf (http://www.ffuenf.de)
* @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
class Ffuenf_DevTools_Test_Helper_Data extends EcomDev_PHPUnit_Test_Case
{
  /**
  * Tests is extension active
  *
  * @test
  * @loadFixture
  */
  public function testIsExtensionActive()
  {
    $this->assertTrue(
      Mage::helper('ffuenf_devtools')->isExtensionActive(),
      'Extension is not active please check config'
    );
  }

  /**
  * Tests is n98-magerun available
  *
  * @test
  * @loadFixture
  */
  public function testIsN98MagerunAvailable()
  {
    $this->assertTrue(
      Mage::helper('ffuenf_devtools')->isN98MagerunAvailable(),
      'No valid n98-magerun found'
    );
  }
}