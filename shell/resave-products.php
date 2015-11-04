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
 * @copyright  Copyright (c) 2015 ffuenf (http://www.ffuenf.de)
 * @license    http://opensource.org/licenses/mit-license.php MIT License
 */

require_once 'abstract.php';

class Resave_Products extends Mage_Shell_Abstract
{
    const DEFAULT_BATCH_SIZE = 250;

    /**
     * @param $collection Varien_Data_Collection
     * @param array $callbackForIndividual
     * @param array $callbackAfterBatch
     * @param integer|null $batchSize
     */
    public function walk($collection, array $callbackForIndividual, array $callbackAfterBatch, $batchSize = null)
    {
        if ($batchSize !== null) {
            $batchSize = self::DEFAULT_BATCH_SIZE;
        }
        $collection->setPageSize($batchSize);
        $currentPage = 1;
        $pages = $collection->getLastPageNumber();
        do {
            $collection->setCurPage($currentPage);
            $collection->load();
            foreach ($collection as $item) {
                call_user_func($callbackForIndividual, $item);
            }
            call_user_func($callbackAfterBatch);
            $currentPage++;
            $collection->clear();
        } while ($currentPage <= $pages);
    }
    
    public function run()
    {
        $condition = 'created_at';
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
            array($this, 'batchIndividual'),
            array($this, 'batchAfter'),
            self::DEFAULT_BATCH_SIZE
        );
    }

    public function batchIndividual($model)
    {
        $product = Mage::getModel('catalog/product')->load($model->getId());
        $product->setIsChanged(true);
        echo $product->getId()."\r\n";
        $product->save();
    }

    public function batchAfter()
    {}
}

$shell = new Resave_Products();
$shell->run();