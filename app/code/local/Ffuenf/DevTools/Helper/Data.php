<?php
/**
* Magento
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@magentocommerce.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade Magento to newer
* versions in the future. If you wish to customize Magento for your
* needs please refer to http://www.magentocommerce.com for more information.
*
* @category    Ffuenf
* @package     Ffuenf_DevTools
* @author      Achim Rosenhagen <a.rosenhagen@ffuenf.de>
* @copyright   Copyright (c) 2015 ffuenf (http://www.ffuenf.de)
* @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/

class Ffuenf_DevTools_Helper_Data extends Mage_Core_Helper_Abstract
{
  /**
  * Path for the config for extension active status
  */
  const CONFIG_EXTENSION_ACTIVE = 'ffuenf_devtools/general/enable';

  /**
  * Variable for if the extension is active
  *
  * @var bool
  */
  protected $bExtensionActive;

  /**
  * Check to see if the extension is active
  *
  * @return bool
  */
  public function isExtensionActive()
  {
    if ($this->bExtensionActive === null) {
      $this->bExtensionActive = Mage::getStoreConfigFlag(self::CONFIG_EXTENSION_ACTIVE);
    }
    return $this->bExtensionActive;
  }

  /**
  * Variable for if n98-magerun is available
  *
  * @var bool
  */
  protected $bN98MagerunAvailable;

  /**
  * Checks if n98-magerun is available
  */
  public function isN98MagerunAvailable()
  {
    $output = $this->runN98Magerun(array('--version'));
    if (!isset($output[0]) || strpos($output[0], 'n98-magerun version') === false) {
      $this->bN98MagerunAvailable = true;
    } else {
      $this->bN98MagerunAvailable = false;
    }
    return $this->bN98MagerunAvailable;
  }

  /**
  * Checks if n98-magerun is present and returns the version number
  */
  public function checkN98Magerun()
  {
    $output = $this->runN98Magerun(array('--version'));
    if (!$this->getN98MagerunPath()) {
      Mage::throwException('No valid n98-magerun found');
    }
    $matches = array();
    preg_match('/(\d+\.\d+\.\d)/', $output[0], $matches);
    return $matches[1];
  }

  /**
  * Get n98-magerun path
  *
  * @return string
  * @throws Mage_Core_Exception
  */
  public function getN98MagerunPath() {
    $pathN98 = Mage::getStoreConfig('ffuenf_devtools/n98magerun/path');
    $baseDir = Mage::getBaseDir();
    $path = $baseDir . DS . $pathN98;
    if (!is_file($path)) {
      Mage::throwException('Could not find n98-magerun at ' . $path);
    }
    return $path;
  }

  public function runN98Magerun($options=array()) {
    array_unshift($options, '--root-dir='.Mage::getBaseDir());
    array_unshift($options, '--no-interaction');
    array_unshift($options, '--no-ansi');
    $output = array();
    exec('php -d ' . $this->getN98MagerunPath() . ' ' . implode(' ', $options), $output);
    return $output;
  }
}