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

class Ffuenf_DevTools_Adminhtml_Devtools_BatchController extends Ffuenf_DevTools_Controller_Abstract
{

    protected function _initAction()
    {
        $helper = Mage::helper('ffuenf_devtools');
        $this->loadLayout()->_setActiveMenu('devtools/resave_products')->_addBreadcrumb($helper->__('Resave Products'), $helper->__('Resave Products'));
        return $this;
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('devtools/resave_products');
        $this->_addContent($this->getLayout()->createBlock('adminhtml/template')->setTemplate('ffuenf/devtools/resave_products.phtml'));
        $this->renderLayout();
    }

    public function runAction()
    {
        $total       = 0;
        $cnt         = 0;
        $message     = '';
        $isCompleted = false;
        $id          = $this->getRequest()->getParam('batchId');
        
        $batch = Mage::getModel('devtools/batch')->load($id);
        if ($batch->getId()) {
            try {
                $hasGenerated = $batch->generate();
                $cnt          = $batch->getInfoCnt();
                $total        = $batch->getInfoTotal();
                if ($hasGenerated) {
                    $message = $this->__('The batch has been processed');
                    $isCompleted = true;
                } else if (!$total) {
                    $message = $this->__('There are no items to process.');
                    $isCompleted = true;
                } elseif (!$cnt) {
                    $message = $this->__('The batch processing has been started. %d items will be processed.', $total);
                } else {
                    // @TODO
                    // 1) get max exec time in seconds
                    // 2) check how many seconds it takes to generate N products
                    // 3) offer to increase the batch size
                    $message = $this->__('Batch processing is in progress. %d of %d items have been processed.', $cnt, $total);
                }
            } catch (Exception $e) {
                $message = $this->__('Please check the following error and try again:<br>%s', $e->getMessage());
                $isCompleted = true;
                $batch->setStatus(Ffuenf_DevTools_Model_Batch::STATE_ERROR);
                $batch->save();
            }
        } else {
            $total = 0;
            $cnt = 0;
            $isCompleted = true;
            $message = $this->__('Please provide a valid batch ID.');
        }
        $progress = 0;
        if ($total) {
            $progress = 100 * $cnt / $total;
        }
        $result = array(
            'total'       => $total,
            'progress'    => $progress,
            'log'         => $message,
            'isCompleted' => $isCompleted
        );
        $json = Zend_Json::encode($result);
        $this->getResponse()->setBody($json);
    }

    public function stopAction()
    {
        $id = $this->getRequest()->getParam('batchId');
        $batch = Mage::getModel('devtools/batch')->load($id);
        $batch->setStatus(Ffuenf_DevTools_Model_Batch::STATE_READY);
        $batch->save();
    }
}
