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

class Ffuenf_DevTools_BackupController extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction()
    {
        $this->loadLayout()->_setActiveMenu('devtools/backup')->_addBreadcrumb(Mage::helper('adminhtml')->__('Backup Manager'), Mage::helper('adminhtml')->__('Backup Manager'));
        return $this;
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('adminhtml/template')->setTemplate('devtools/backup.phtml'));
        $this->renderLayout();
    }

    /**
     * mysql backup action
     */
    public function backupAction()
    {
        $path = Mage::helper('ffuenf_devtools')->getDatabaseDumpScriptPath();
        if (file_exists($path)) {
            exec($path);  
            Mage::getSingleton('core/session')->addSuccess('Backup successfully created');
        } else {
            Mage::getSingleton('core/session')->addError('Could not open backup script.');
        }
        $this->_redirect('devtools/backup/index');
    }
}

