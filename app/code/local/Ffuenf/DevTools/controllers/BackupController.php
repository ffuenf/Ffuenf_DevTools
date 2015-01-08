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

class Ffuenf_DevTools_BackupController extends Mage_Adminhtml_Controller_Action {
  protected function _initAction() {
    $this->loadLayout()->_setActiveMenu('devtools/backup')->_addBreadcrumb(Mage::helper('adminhtml')->__('Backup Manager'), Mage::helper('adminhtml')->__('Backup Manager'));
    return $this;
  }

  public function indexAction() {
    $this->loadLayout();
    $this->_addContent($this->getLayout()->createBlock('adminhtml/template')->setTemplate('devtools/backup.phtml'));
    $this->renderLayout();
  }
    
  /**
  * mysql backup action
  */
  public function backupAction() {
    $path = '../../shared/dump_database.sh';
    if (file_exists($path)) {
      exec($path);  
      Mage::getSingleton('core/session')->addSuccess('Backup successfully created');
    } else {
      Mage::getSingleton('core/session')->addError('Could not open backup script.');
    }
    $this->_redirect('devtools/backup/index');
  }
}

