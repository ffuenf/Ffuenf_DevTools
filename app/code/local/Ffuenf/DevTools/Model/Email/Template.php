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

// Allow for an override of Aschroder_SMTPPro_Model_Email_Template
if (Mage::helper('core')->isModuleEnabled('Aschroder_SMTPPro') && class_exists('Aschroder_SMTPPro_Model_Email_Template')) {
    class Ffuenf_DevTools_Model_Email_Template_Wrapper extends Aschroder_SMTPPro_Model_Email_Template
    {
    }
// Allow for an override of Aschroder_Email_Model_Email_Template
} elseif (Mage::helper('aschroder_email')->isEnabled() && class_exists('Aschroder_Email_Model_Email_Template')) {
    class Ffuenf_DevTools_Model_Email_Template_Wrapper extends Aschroder_Email_Model_Email_Template
    {
    }
// Allow for an override of Ebizmarts_Mandrill_Model_Email_Template
} elseif (Mage::helper('core')->isModuleEnabled('Ebizmarts_Mandrill') && class_exists('Ebizmarts_Mandrill_Model_Email_Template')) {
    class Ffuenf_DevTools_Model_Email_Template_Wrapper extends Ebizmarts_Mandrill_Model_Email_Template
    {
    }
} else {
    class Ffuenf_DevTools_Model_Email_Template_Wrapper extends Mage_Core_Model_Email_Template
    {
    }
}

class Ffuenf_DevTools_Model_Email_Template extends Ffuenf_DevTools_Model_Email_Template_Wrapper
{
    /**
     * Initialize design information for template processing.
     *
     * @param array $config
     *
     * @return Mage_Core_Model_Template
     */
    public function setDesignConfig(array $config)
    {
        if (isset($config['store'])) {
            $store = Mage::registry('emailoverride.store');
            if (empty($store)) {
                Mage::register('emailoverride.store', $config['store'], true);
            }
        }

        return parent::setDesignConfig($config);
    }
}
