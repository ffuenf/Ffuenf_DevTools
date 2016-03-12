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

class Ffuenf_DevTools_Model_Batch extends Mage_Core_Model_Abstract
{
    const STATE_READY    = 0;
    const STATE_WAITING  = 1;
    const STATE_PROGRESS = 2;
    const STATE_ERROR    = 3;

    protected $_collection;
    protected $_resultData;
    protected $_stepSize = 100;

    public function _construct()
    {
        $this->_init('ffuenf_devtools/batch');
    }

    protected function _getStepSize()
    {
        $batchSize = Mage::getStoreConfig('ffuenf_devtools/batch/batch_size');
        return $batchSize ? $batchSize : $this->_stepSize;
    }

    protected function _beforeGenerate()
    {
        if ($this->getBatchStep() === NULL) {
            $this->setStatus(self::STATE_PROGRESS);
            $this->setBatchStep(0);
            $this->save();
        }
    }

    protected function _getCollection()
    {
        if (!$this->_collection) {
            $this->_collection = Mage::getResourceModel('devtools/batch_collection');
            $this->_collection->init($this);
        }
        return $this->_collection;
    }

    protected function _prepareCollection()
    {
        $offset = $this->getBatchStep() * $this->_getStepSize();
        $this->getCollection()->getSelect()->limit($this->_getStepSize(), $offset);
    }
    
    protected function _generateData()
    {
        foreach ($this->getCollection()->getData() as $data) {
            $record = array();
            // step data: prepare your data
            $this->_resultData[] = $record;
        }
    }

    protected function _afterGenerate()
    {
        if ($this->getBatchStep() == 0) {
            $this->setInfoCount(count($this->_resultData));
            $this->setInfoTotal($this->_collection->getCountItems());
        } else {
            $this->setInfoCount($this->getInfoCount() + count($this->_resultData));
        }
        if (count($this->_resultData) < $this->_getStepSize()) {
            // after all batch finish
            $this->setBatchStep(NULL);
            $this->setStatus(self::STATE_READY);
        } else {
            $this->setBatchStep($this->getBatchStep() + 1);
        }
        $this->save();
    }

    public function generate()
    {
        $this->_beforeGenerate();
        $oldStore = Mage::app()->getStore();
        Mage::app()->setCurrentStore($this->getStoreId());
        $this->_prepareCollection();
        $this->_generateData();
        $this->_afterGenerate();
        $this->save();
        Mage::app()->setCurrentStore($oldStore);
        return count($this->_resultData) < $this->_getStepSize() || count($this->_resultData) == 0;
    }
}
