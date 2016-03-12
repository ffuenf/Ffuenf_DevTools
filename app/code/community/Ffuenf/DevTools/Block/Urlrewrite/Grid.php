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

class Ffuenf_DevTools_Block_Urlrewrite_Grid extends Mage_Adminhtml_Block_Urlrewrite_Grid
{
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('url_rewrite_id');
        $this->setMassactionIdFilter('url_rewrite_id');
        $this->setMassactionIdFieldOnlyIndexValue(true);
        $this->getMassactionBlock()->setFormFieldName('url_rewrites');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('review')->__('Delete'),
                'url'  => $this->getUrl(
                '*/*/massDelete',
                array('ret' => Mage::registry('usePendingFilter') ? 'pending' : 'index')
            ),
            'confirm' => Mage::helper('ffuenf_devtools')->__('Are you sure?')
        ));
    }
}
