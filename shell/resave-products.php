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

require_once 'abstract.php';

class Resave_Products extends Mage_Shell_Abstract
{
    const DEFAULT_BATCH_SIZE = 10000;

    protected $report = array();

    /**
     * @param $collection Varien_Data_Collection
     * @param array $callbackForIndividual
     * @param array $callbackAfterBatch
     */
    protected function walk($collection, array $callbackBeforeBatch, array $callbackForIndividual, array $callbackAfterBatch)
    {
        $collection->setPageSize(self::DEFAULT_BATCH_SIZE);
        $currentPage = 1;
        $pages = $collection->getLastPageNumber();
        do {
            $collection->setCurPage($currentPage);
            $collection->load();
            $report['batch'][$currentPage]['start']['time'] = microtime(true);
            $report['batch'][$currentPage]['start']['memory_usage'] = $this->convert(memory_get_usage(true));
            call_user_func($callbackBeforeBatch, $currentPage);
            foreach ($collection as $item) {
                call_user_func($callbackForIndividual, $item, $currentPage);
            }
            call_user_func($callbackAfterBatch, $currentPage);
            
            $currentPage++;
            $collection->clear();
        } while ($currentPage <= $pages);
    }
    
    public function run()
    {
        $condition = Mage::helper('ffuenf_devtools')->getResaveProductsTimeframeType();
        $collection = Mage::getResourceModel('catalog/product_collection')
        ->addFieldToFilter(
            $condition, array(
                'gt' => date("Y-m-d H:i:s", Mage::helper('ffuenf_devtools')->getResaveProductsTimeframeFrom())
            )
        )->addFieldToFilter(
            $condition, array(
                'lt' => date("Y-m-d H:i:s", Mage::helper('ffuenf_devtools')->getResaveProductsTimeframeTo())
            )
        );
        $this->walk(
            $collection,
            array($this, 'batchBefore'),
            array($this, 'batchIndividual'),
            array($this, 'batchAfter')
        );
    }

    protected function batchBefore($currentPage)
    {
        $this->report[$currentPage] = array();
    }

    public function batchIndividual($item, $currentPage)
    {
        $_product = Mage::getModel('catalog/product')->load($item->getId());
        $_product->setIsChanged(true);
        $this->report[$currentPage]['ids'][] = $_product->getId();
        $_product->save();
        $_product->clearInstance();
    }

    public function batchAfter($currentPage)
    {
        Mage::log('Resaved products: ' . implode(',', $this->report[$currentPage]['ids']), null, 'resave-products.log');
    }
}
$shell = new Resave_Products();
$shell->run();
