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

class Ffuenf_DevTools_Adminhtml_DevTools_BackupController extends Ffuenf_DevTools_Controller_Abstract
{
    protected function _initAction()
    {
        $helper = Mage::helper('ffuenf_devtools');
        $this->loadLayout()->_setActiveMenu('devtools/backup')->_addBreadcrumb($helper->__('Backup Manager'), $helper->__('Backup Manager'));

        return $this;
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('devtools/backup');
        $this->_addContent($this->getLayout()->createBlock('adminhtml/template')->setTemplate('devtools/backup.phtml'));
        $this->renderLayout();
    }

    /**
     * mysql backup action.
     */
    public function backupAction()
    {
        $helper = Mage::helper('ffuenf_devtools');
        $path = $helper->getDatabaseDumpScriptPath();
        if (file_exists($path)) {
            exec($path);
            Mage::getSingleton('core/session')->addSuccess($helper->__('Backup successfully created'));
        } else {
            Mage::getSingleton('core/session')->addError($helper->__('Could not open backup script.'));
        }
        $this->_redirect('*/*/');
    }
}
