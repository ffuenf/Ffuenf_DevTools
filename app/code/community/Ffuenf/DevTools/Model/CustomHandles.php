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

class Ffuenf_DevTools_Model_CustomHandles
{
    /**
     * Cached helper
     *
     * @var Ffuenf_DevTools_Helper_Data
     */
    protected $_helper;

    /**
     * Get helper
     *
     * @return Ffuenf_DevTools_Helper_Data
     */
    protected function _getHelper()
    {
        if (is_null($this->_helper)) {
            $this->_helper = Mage::helper('ffuenf_devtools');
        }
        return $this->_helper;
    }

    /**
     * @param $observer Varien_Event_Observer
     * @fire adminhtml_controller_action_predispatch_start
     */
    public function addCustomHandles(Varien_Event_Observer $observer)
    {
        /** @var Mage_Core_Model_Layout_Update $updateManager */
        $updateManager = $observer->getLayout()->getUpdate();
        $this->_addCustomerGroupHandle($updateManager);
        $this->_addCustomerGenderHandle($updateManager);
        $this->_addCustomerBirthdayHandle($updateManager);
        $this->_addCustomerIsSubscribedHandle($updateManager);
        $this->_addSeasonHandle($updateManager);
        $this->_addEmptyCategoryHandle($observer);
        $this->_addEmptyCartHandle($observer);
        $this->_addEmptySearchHandle($observer);
        
        $action = $observer->getEvent()->getAction(); /* @var $action Mage_Core_Controller_Varien_Action */
        switch ($action->getFullActionName()) {
            case 'catalog_category_view':
                $this->_addCategoryNameHandle($observer);
                break;
            case 'catalog_product_view':
                $this->_addAttributeSetHandle($observer);
                break;
            case 'cms_page_view':
                $this->_addCmsPageHandle($observer);
                break;
            default:
        }
        $this->_addActionHandles($observer);
    }

    /**
     * @param $updateManager Mage_Core_Model_Layout_Update
     */
    private function _addCustomerGroupHandle(Mage_Core_Model_Layout_Update $updateManager)
    {
        $customerHelper = Mage::helper('customer');
        $currentCustomer = $customerHelper->getCurrentCustomer();
        if ($groupId = $currentCustomer->getGroupId()) {
            $customerGroup = Mage::getModel('customer/group')->load($groupId);
            $groupCode = strtolower(preg_replace("/[^-a-zA-Z0-9]+/", "", $customerGroup->getCode()));
            $updateManager->addHandle("customer_group_{$groupCode}");
        }
    }

    /**
     * @param $updateManager Mage_Core_Model_Layout_Update
     */
    private function _addCustomerGenderHandle(Mage_Core_Model_Layout_Update $updateManager)
    {
        $customerHelper = Mage::helper('customer');
        if ($customerHelper->isLoggedIn()) {
            /** @var Mage_Customer_Model_Customer $currentCustomer */
            $currentCustomer = $customerHelper->getCurrentCustomer();
            if ($genderOptionId = $currentCustomer->getGender()) {
                $gender = strtolower($currentCustomer->getResource()
                    ->getAttribute('gender')
                    ->getSource()
                    ->getOptionText($genderOptionId));
                $updateManager->addHandle("customer_gender_{$gender}");
            }
        }
    }

    /**
     * @param $updateManager Mage_Core_Model_Layout_Update
     */
    private function _addCustomerBirthdayHandle(Mage_Core_Model_Layout_Update $updateManager)
    {
        $customerHelper = Mage::helper('customer');
        if ($customerHelper->isLoggedIn()) {
            /** @var Mage_Customer_Model_Customer $currentCustomer */
            $currentCustomer = $customerHelper->getCurrentCustomer();
            if ($birthDate = $currentCustomer->getDob()) {
                $helper = $this->_getHelper();
                if ($helper->isBirthday($birthDate)) {
                    $updateManager->addHandle("customer_birthday");
                }
            }
        }
    }

    /**
     * @param $updateManager Mage_Core_Model_Layout_Update
     */
    private function _addCustomerIsSubscribedHandle(Mage_Core_Model_Layout_Update $updateManager)
    {
        $customerHelper = Mage::helper('customer');
        if ($customerHelper->isLoggedIn()) {
            /** @var Mage_Customer_Model_Customer $currentCustomer */
            $currentCustomer = $customerHelper->getCurrentCustomer();
            $subscriber = Mage::getModel('newsletter/subscriber')->loadByCustomer($currentCustomer);
            if ($subscriber->isSubscribed()) {
                $updateManager->addHandle("customer_subscribed");
                return;
            }
            $updateManager->addHandle("customer_not_subscribed");
        }
    }

    /**
     * @param $updateManager Mage_Core_Model_Layout_Update
     */
    private function _addSeasonHandle(Mage_Core_Model_Layout_Update $updateManager)
    {
        $helper = $this->_getHelper();
        $season = $helper->getSeason();
        $updateManager->addHandle("season_{$season}");
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    protected function _addCategoryNameHandle($observer)
    {
        $category = Mage::registry('current_category');
        if (!($category instanceof Mage_Catalog_Model_Category)) {
            return;
        }
        $niceName = str_replace('-', '_', $category->formatUrlKey($category->getName()));
        /* @var $update Mage_Core_Model_Layout_Update */
        $update = $observer->getEvent()->getLayout()->getUpdate();
        $update->addHandle('CATEGORY_' . $niceName);
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    protected function _addAttributeSetHandle($observer)
    {
        $product = Mage::registry('current_product');
        /**
         * Return if it is not product page
         */
        if (!($product instanceof Mage_Catalog_Model_Product)) {
            return;
        }
        $attributeSet = Mage::getModel('eav/entity_attribute_set')->load($product->getAttributeSetId());
        $niceName = str_replace('-', '_', $product->formatUrlKey($attributeSet->getAttributeSetName()));
        /* @var $update Mage_Core_Model_Layout_Update */
        $update = $observer->getEvent()->getLayout()->getUpdate();
        $update->addHandle('PRODUCT_ATTRIBUTE_SET_' . $niceName);
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    protected function _addCmsPageHandle($observer)
    {
        $pageId = Mage::app()->getRequest()->getParam('page_id');
        if ($pageId) {
            $page = Mage::getSingleton('cms/page');
            $page->setStoreId(Mage::app()->getStore()->getId());
            $page->load($pageId); /* @var $page Mage_Cms_Model_Page */
            /* @var $update Mage_Core_Model_Layout_Update */
            $update = $observer->getEvent()->getLayout()->getUpdate();
            $update->addHandle('CMS_PAGE_' . $page->getIdentifier());
        }
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    protected function _addActionHandles($observer)
    {
        $action = $observer->getEvent()->getAction(); /* @var $action Mage_Core_Controller_Varien_Action */
        $fullActionName = $action->getFullActionName();
        $websiteCode = Mage::app()->getWebsite()->getCode();
        $storeCode = Mage::app()->getStore()->getCode();
        /* @var $update Mage_Core_Model_Layout_Update */
        $update = $observer->getEvent()->getLayout()->getUpdate();
        $update->addHandle('WEBSITE_' . $websiteCode . '_' . $fullActionName);
        $update->addHandle('STORE_' . $storeCode . '_' . $fullActionName);
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    protected function _addEmptyCategoryHandle($observer)
    {
        $category = Mage::registry('current_category');
        if (!($category instanceof Mage_Catalog_Model_Category)) {
            return;
        }
        $numProducts = (bool)Mage::helper('catalog')->getCategory()->getProductCollection()->count();
        if (!$numProducts) {
            /* @var $update Mage_Core_Model_Layout_Update */
            $update = $observer->getEvent()->getLayout()->getUpdate();
            $update->addHandle('catalog_category_view_empty');
        }
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    protected function _addEmptySearchHandle($observer)
    {
        $numResults = (bool)Mage::helper('catalogsearch')->getQuery()->getNumResults();
        if (!$numResults) {
            /* @var $update Mage_Core_Model_Layout_Update */
            $update = $observer->getEvent()->getLayout()->getUpdate();
            $update->addHandle('catalogsearch_result_index_empty');
        }
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    protected function _addEmptyCartHandle($observer)
    {
        $numCartItems = (bool)Mage::helper('checkout/cart')->getItemsCount();
        if (!$numCartItems) {
            /* @var $update Mage_Core_Model_Layout_Update */
            $update = $observer->getEvent()->getLayout()->getUpdate();
            $update->addHandle('checkout_cart_index_empty');
        }
    }
}
