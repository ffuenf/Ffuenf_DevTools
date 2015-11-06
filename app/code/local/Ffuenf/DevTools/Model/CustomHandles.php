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
    }

    /**
     * @param $updateManager Mage_Core_Model_Layout_Update
     */
    private function _addCustomerGroupHandle(Mage_Core_Model_Layout_Update $updateManager)
    {
        $customerHelper = Mage::helper('customer');
        $currentCustomer = $customerHelper->getCurrentCustomer();
        if ($groupId = $currentCustomer->getGroupId()) {
            $customerGroup = Mage::getModel('customer/group')->load($groupId );
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
}
