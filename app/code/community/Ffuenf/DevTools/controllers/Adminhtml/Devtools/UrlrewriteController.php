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

require_once('Mage/Adminhtml/controllers/UrlrewriteController.php');
class Ffuenf_Devtools_Adminhtml_Devtools_UrlrewriteController extends Mage_Adminhtml_UrlrewriteController
{
    /**
     * mass delete action, deletes the selected url rewrites
     */
    public function massDeleteAction()
    {
        $reviewsIds = $this->getRequest()->getParam('url_rewrites');
        $session    = Mage::getSingleton('adminhtml/session');
        if (!is_array($reviewsIds)) {
            $session->addError(Mage::helper('ffuenf_devtools')->__('Please select rewrite(s).'));
        } else {
            try {
                foreach ($reviewsIds as $reviewId) {
                    $model = Mage::getModel('core/url_rewrite')->load($reviewId);
                    $model->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Total of %d record(s) have been deleted.', count($reviewsIds))
                );
            } catch (Exception $e) {
                $session->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/' . $this->getRequest()->getParam('ret', 'index'));
    }

    /**
     * check whether the current user is allowed to access this controller
     * @return bool
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('ffuenf_devtools');
    }
}
