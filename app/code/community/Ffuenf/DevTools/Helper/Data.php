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

class Ffuenf_DevTools_Helper_Data extends Ffuenf_Common_Helper_Core
{
    const CONFIG_EXTENSION_ACTIVE = 'ffuenf_devtools/general/enable';
    const CONFIG_EXTENSION_MAGERUNPATH = 'ffuenf_devtools/magerun/path';
    const CONFIG_EXTENSION_BACKUP_DUMPDATABASESCRIPTPATH = 'ffuenf_devtools/backup/dump_database_script_path';
    const CONFIG_EXTENSION_RESAVEPRODUCTS_TIMEFRAMEFROM = 'ffuenf_devtools/resaveproducts/timeframefrom';
    const CONFIG_EXTENSION_RESAVEPRODUCTS_TIMEFRAMETO = 'ffuenf_devtools/resaveproducts/timeframeto';
    const CONFIG_EXTENSION_RESAVEPRODUCTS_TIMEFRAMETYPE = 'ffuenf_devtools/resaveproducts/timeframetype';
    const CONFIG_EXTENSION_HEADERBAR_ACTIVE = 'ffuenf_devtools/headerbar/enable';
    const CONFIG_EXTENSION_HEADERBAR_BRANCH = 'ffuenf_devtools/headerbar/branch';
    const CONFIG_EXTENSION_HEADERBAR_COMMIT = 'ffuenf_devtools/headerbar/commit';
    const CONFIG_EXTENSION_HEADERBAR_TAG = 'ffuenf_devtools/headerbar/tag';

    private $_domain = null;
    private $_className = null;

    /**
     * Variable for if the extension is active.
     *
     * @var bool
     */
    protected $_bExtensionActive;

    /**
     * Variable for if magerun is available.
     *
     * @var bool
     */
    protected $_bMagerunAvailable;

    /**
     * Variable for the magerun system path.
     *
     * @var string
     */
    protected $_sMagerunPath;

    /**
     * Variable for the path to the dump_database shell script.
     *
     * @var string
     */
    protected $_sDumpDatabaseScriptPath;

    /**
     * Variable for the timeframe of resaving products
     *
     * @var string
     */
    protected $_sResaveProductsTimeframeFrom;

    /**
     * Variable for the timeframe of resaving products
     *
     * @var string
     */
    protected $_sResaveProductsTimeframeTo;

    /**
     * Variable for the timeframe type of resaving products
     *
     * @var string
     */
    protected $_sResaveProductsTimeframeType;

    /**
     * Variable for the headerbar.
     *
     * @var bool
     */
    protected $_bHeaderbarActive;

    /**
     * Variable for the current git branch.
     *
     * @var string
     */
    protected $_sHeaderbarBranch;

    /**
     * Variable for the current git commit.
     *
     * @var string
     */
    protected $_sHeaderbarCommit;

    /**
     * Variable for the current git tag.
     *
     * @var string
     */
    protected $_sHeaderbarTag;

    /**
     * Check to see if the extension is active.
     *
     * @return bool
     */
    public function isExtensionActive()
    {
        return $this->getStoreFlag(self::CONFIG_EXTENSION_ACTIVE, '_bExtensionActive');
    }

    /**
     * Checks if magerun is available.
     *
     * @return bool
     */
    public function isMagerunAvailable()
    {
        $output = $this->runMagerun(array('--version'));
        if (!isset($output[0]) || strpos($output[0], 'n98-magerun version') === false) {
            $this->_bMagerunAvailable = true;
        } else {
            $this->_bMagerunAvailable = false;
        }

        return $this->_bMagerunAvailable;
    }

    /**
     * Checks if magerun is present and returns the version number.
     *
     * @return string
     *
     * @throws Mage_Core_Exception
     */
    public function checkMagerun()
    {
        $output = $this->runMagerun(array('--version'));
        if (!$this->getMagerunPath()) {
            Mage::throwException('No valid magerun found');
        }
        $matches = array();
        preg_match('/(\d+\.\d+\.\d)/', $output[0], $matches);

        return $matches[1];
    }

    /**
     * Get magerun path.
     *
     * @return string
     *
     * @throws Mage_Core_Exception
     */
    public function getMagerunPath()
    {
        $baseDir = Mage::getBaseDir();
        $path = $baseDir . DS . $this->getStoreConfig(self::CONFIG_EXTENSION_MAGERUNPATH, '_sMagerunPath');
        if (!is_file($path)) {
            Mage::throwException('Could not find magerun at ' . $path);
        }

        return $path;
    }

    /**
     * Run magerun command.
     *
     * @param array<string>
     */
    public function runMagerun($options = array())
    {
        array_unshift($options, '--root-dir=' . Mage::getBaseDir());
        array_unshift($options, '--no-interaction');
        array_unshift($options, '--no-ansi');
        $output = array();
        exec('php -d ' . $this->getMagerunPath() . ' ' . implode(' ', $options), $output);

        return $output;
    }

    /**
     * Get database_dump script path.
     *
     * @return string
     *
     * @throws Mage_Core_Exception
     */
    public function getDatabaseDumpScriptPath()
    {
        return $this->getStoreConfig(self::CONFIG_EXTENSION_BACKUP_DUMPDATABASESCRIPTPATH, '_sDumpDatabaseScriptPath');
    }

    /**
     * @param string  $localeCode
     * @param string  $fileName
     * @param string|int|Mage_Core_Model_Store|null $store  (optional)
     *
     * @return string|null
     */
    public function getLocaleOverrideFile($localeCode, $fileName, $store = null)
    {
        $paths = $this->getLocalePaths($store);
        $localCodes = $localeCode === 'en_US' ? array($localeCode) : array($localeCode, 'en_US');
        foreach ($localCodes as $localeCode) {
            foreach ($paths as $path) {
                $filePath = $path . DS . $localeCode . DS . $fileName;
                if (!empty($filePath) && file_exists($filePath)) {
                    return $filePath;
                }
            }
        }
        return null;
    }

    /**
     * @param string|int|Mage_Core_Model_Store|null $store (optional)
     *
     * @return array
     */
    public function getLocalePaths($store = null)
    {
        $paths = array();
        $design = $this->getDesign($store);
        $paths[] = Mage::getBaseDir('design').DS.'frontend'.DS.$design['package'].DS.$design['theme'].DS.'locale';
        // Check for fallback support
        if ($this->supportsDesignFallback()) {
            $fallbackModel = Mage::getModel('core/design_fallback');
            if (!empty($fallbackModel)) {
                $fallbackSchemes = $fallbackModel->getFallbackScheme('frontend', $design['package'], $design['theme']);
                if (!empty($fallbackSchemes)) {
                    foreach ($fallbackSchemes as $scheme) {
                        if (!isset($scheme['_package']) || !isset($scheme['_theme'])) continue;
                        $paths[] = Mage::getBaseDir('design').DS.'frontend'.DS.$scheme['_package'].DS.$scheme['_theme'].DS.'locale';
                    }
                }
            }
        }
        $paths[] = Mage::getBaseDir('design').DS.'frontend'.DS.$design['package'].DS.'default'.DS.'locale';
        $paths[] = Mage::getBaseDir('design').DS.'frontend'.DS.'default'.DS.'default'.DS.'locale';
        $paths[] = Mage::getBaseDir('design').DS.'frontend'.DS.'base'.DS.'default'.DS.'locale';
        $paths[] = Mage::getBaseDir('locale');
        return $paths;
    }

    /**
     * @param string|int|Mage_Core_Model_Store|null $store (optional)
     *
     * @return array
     */
    public function getDesign($store = null)
    {
        if (empty($store)) {
            $store = Mage::registry('emailoverride.store');
        }
        $packageName = null;
        $theme = null;
        if (Mage::app()->getStore()->isAdmin() == false) {
            $package = Mage::getSingleton('core/design_package');
            $originalArea = $package->getArea();
            $originalStore = $package->getStore();
            if (!empty($store)) {
                $package->setStore($store);
            }
            $package->setArea('frontend');
            $packageName = $package->getPackageName();
            $theme = $package->getTheme('default');
            $package->setArea($originalArea);
            $package->setStore($originalStore);
        }
        if (empty($packageName) || in_array($theme, array('base', 'default'))) {
            $packageName = Mage::getStoreConfig('design/package/name', $store);
        }
        if (empty($theme) || in_array($theme, array('default'))) {
            $theme = Mage::getStoreConfig('design/theme/locale', $store);
        }
        if (empty($theme)) {
            $theme = Mage::getStoreConfig('design/theme/default', $store);
        }
        if (empty($packageName)) $packageName = 'default';
        if (empty($theme)) $theme = 'default';
        return array(
            'package' => $packageName,
            'theme' => $theme,
        );
    }

    /**
     * @return string
     */
    public function getPatches()
    {
        return Mage::getModel('ffuenf_devtools/patches')->getPatches();
    }

    /**
     * Check to see if the headerbar is active.
     *
     * @return bool
     */
    public function isHeaderbarActive()
    {
        return $this->getStoreFlag(self::CONFIG_EXTENSION_HEADERBAR_ACTIVE, '_bHeaderbarActive');
    }

    /**
     * @return string
     */
    public function getTag()
    {
        $tag = '';
        exec('git describe --abbrev=0 --tags', $tag);
        $this->_sHeaderbarTag = array_shift($tag);

        return $this->getStoreConfig(self::CONFIG_EXTENSION_HEADERBAR_TAG, '_sHeaderbarTag');
    }

    /**
     * @return string
     */
    public function getBranch()
    {
        exec('git branch', $lines);
        if (is_array($lines)) {
            foreach ($lines as $line) {
                if (strpos($line, '*') === 0) {
                    $branch = ltrim($line, '* ');
                    $this->_sHeaderbarBranch = $branch;
                    break;
                }
            }
        }

        return $this->getStoreConfig(self::CONFIG_EXTENSION_HEADERBAR_BRANCH, '_sHeaderbarBranch');
    }

    /**
     * @param string $flavour (optional)
     *
     * @return string
     */
    public function getCommit($flavour = 'short')
    {
        switch ($flavour) {
            case 'full':
                exec('git log -1', $line);
                $hash = str_replace('commit ', '', $line[0]);
                break;
            default:
                exec('git describe --always', $line);
                $hash = array_shift($line);
                break;
        }
        $this->_sHeaderbarCommit = $hash;

        return $this->getStoreConfig(self::CONFIG_EXTENSION_HEADERBAR_COMMIT, '_sHeaderbarCommit');
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        if (null !== $this->_className) {
            return $this->_className;
        }
        $this->_className = '';
        $domain = $this->getDomain();
        $environments = array(
            'production',
            'staging',
            'development',
        );
        foreach ($environments as $env) {
            if ($domain === Mage::getStoreConfig('ffuenf_devtools/headerbar/' . $env . '_path')) {
                $this->_className = $env;
                break;
            }
        }

        return $this->_className;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        if (null !== $this->_domain) {
            return $this->_domain;
        }
        $path = strtolower(rtrim(trim(Mage::getStoreConfig('web/unsecure/base_url')), '/'));
        $this->_domain = str_replace(array('http://', 'https://'), '', $path);
        return $this->_domain;
    }

    /**
     * @return boolean
     */
    public function supportsDesignFallback()
    {
        // Check for the right file
        if (file_exists(BP . '/app/code/core/Mage/Core/Model/Design/Fallback.php') === false) {
            return false;
        }
        return true;
    }
    
    /**
     * Get timeframe of resaving products.
     *
     * @throws Mage_Core_Exception
     */
    public function getResaveProductsTimeframeFrom()
    {
        return strtotime($this->getStoreConfig(self::CONFIG_EXTENSION_RESAVEPRODUCTS_TIMEFRAMEFROM, '_sResaveProductsTimeframeFrom'));
    }

    /**
     * Get timeframe of resaving products.
     *
     * @throws Mage_Core_Exception
     */
    public function getResaveProductsTimeframeTo()
    {
        return strtotime($this->getStoreConfig(self::CONFIG_EXTENSION_RESAVEPRODUCTS_TIMEFRAMETO, '_sResaveProductsTimeframeTo'));
    }

    /**
     * Get timeframe type of resaving products.
     *
     * @throws Mage_Core_Exception
     * @return string
     */
    public function getResaveProductsTimeframeType()
    {
        return $this->getStoreConfig(self::CONFIG_EXTENSION_RESAVEPRODUCTS_TIMEFRAMETYPE, '_sResaveProductsTimeframeType');
    }

    public function isBirthday($birthdate)
    {
        if (empty($birthdate)) {
            return false;
        }
        $currentDayMonth = date("m-d");
        $birthdateDayMonth = substr($birthdate, 5, 5);
        return $currentDayMonth == $birthdateDayMonth;
    }

    public function getSeason()
    {
        if ($this->_isWinter()) {
            return 'winter';
        }
        if ($this->_isSpring()) {
            return 'spring';
        }
        if ($this->_isSummer()) {
            return 'summer';
        }
        if ($this->_isAutumn()) {
            return 'autumn';
        }
    }

    private function _isWinter()
    {
        $currentYear = date("Y");
        return $this->_isDateInRange("{$currentYear}-12-01", "{$currentYear}-12-31")
            || $this->_isDateInRange("{$currentYear}-01-01", "{$currentYear}-02-29");
    }

    private function _isSpring()
    {
        $currentYear = date("Y");
        return $this->_isDateInRange("{$currentYear}-03-01", "{$currentYear}-05-31");
    }

    private function _isSummer()
    {
        $currentYear = date("Y");
        return $this->_isDateInRange("{$currentYear}-06-01", "{$currentYear}-08-31");
    }

    private function _isAutumn()
    {
        $currentYear = date("Y");
        return $this->_isDateInRange("{$currentYear}-09-01", "{$currentYear}-11-30");
    }

    private function _isDateInRange($startDate, $endDate, $dateToCheck = null)
    {
        if (is_null($dateToCheck)) {
            $dateToCheck = time();
        }
        if (is_string($dateToCheck)) {
            $dateToCheck = strtotime($dateToCheck);
        }
        $startTimestamp = strtotime($startDate);
        $endTimestamp = strtotime($endDate);
        return (($dateToCheck >= $startTimestamp) && ($dateToCheck <= $endTimestamp));
    }
}
