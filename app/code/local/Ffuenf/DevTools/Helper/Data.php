<?php
/**
 * Ffuenf_DevTools extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category   Ffuenf
 * @package    Ffuenf_DevTools
 * @author     Achim Rosenhagen <a.rosenhagen@ffuenf.de>
 * @copyright  Copyright (c) 2015 ffuenf (http://www.ffuenf.de)
 * @license    http://opensource.org/licenses/mit-license.php MIT License
 */

class Ffuenf_DevTools_Helper_Data extends Ffuenf_DevTools_Helper_Core
{

    const CONFIG_EXTENSION_ACTIVE                           = 'ffuenf_devtools/general/enable';
    const CONFIG_EXTENSION_MAGERUNPATH                      = 'ffuenf_devtools/magerun/path';
    const CONFIG_EXTENSION_BACKUP_DUMPDATABASESCRIPTPATH    = 'ffuenf_devtools/backup/dump_database_script_path';
    const CONFIG_EXTENSION_HEADERBAR_BRANCH                 = 'ffuenf_devtools/headerbar/branch';
    const CONFIG_EXTENSION_HEADERBAR_COMMIT                 = 'ffuenf_devtools/headerbar/commit';
    const CONFIG_EXTENSION_HEADERBAR_TAG                    = 'ffuenf_devtools/headerbar/tag';

    /**
     * Variable for if the extension is active
     *
     * @var bool
     */
    protected $bExtensionActive;

    /**
     * Variable for the magerun system path
     *
     * @var string
     */
    protected $sMagerunPath;

    /**
     * Variable for the path to the dump_database shell script
     *
     * @var string
     */
    protected $sDumpDatabaseScriptPath;

    /**
     * Variable for the current git branch
     *
     * @var string
     */
    protected $sHeaderbarBranch;

    /**
     * Variable for the current git commit
     *
     * @var string
     */
    protected $sHeaderbarCommit;

    /**
     * Variable for the current git tag
     *
     * @var string
     */
    protected $sHeaderbarTag;

    private $_domain = null;
    private $_className = null;

    /**
     * Check to see if the extension is active
     *
     * @return bool
     */
    public function isExtensionActive()
    {
        return $this->getStoreFlag(self::CONFIG_EXTENSION_ACTIVE, 'bExtensionActive');
    }

    /**
     * Variable for if magerun is available
     *
     * @var bool
     */
    protected $bMagerunAvailable;

    /**
     * Checks if magerun is available
     *
     * @return bool
     */
    public function isMagerunAvailable()
    {
        $output = $this->runMagerun(array('--version'));
        if (!isset($output[0]) || strpos($output[0], 'n98-magerun version') === false) {
            $this->bMagerunAvailable = true;
        } else {
            $this->bMagerunAvailable = false;
        }
        return $this->bMagerunAvailable;
    }

    /**
     * Checks if magerun is present and returns the version number
     *
     * @return string
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
     * Get magerun path
     *
     * @return string
     * @throws Mage_Core_Exception
     */
    public function getMagerunPath()
    {
        $baseDir = Mage::getBaseDir();
        $path = $baseDir . DS . $this->getStoreConfig(self::CONFIG_EXTENSION_MAGERUNPATH, 'sMagerunPath');
        if (!is_file($path)) {
            Mage::throwException('Could not find magerun at ' . $path);
        }
        return $path;
    }

    /**
     * Run magerun command
     *
     * @param string[]
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
     * Get database_dump script path
     *
     * @return string
     * @throws Mage_Core_Exception
     */
    public function getDatabaseDumpScriptPath()
    {
        return $this->getStoreConfig(self::CONFIG_EXTENSION_BACKUP_DUMPDATABASESCRIPTPATH, 'sDumpDatabaseScriptPath');
    }

    /**
     * @param string $localeCode
     * @param string $fileName
     * @param string|integer|Mage_Core_Model_Store $store (optional)
     *
     * @return string|null
     */
    public function getLocaleOverrideFile($localeCode, $fileName, $store = null)
    {
        $paths = $this->getLocalePaths($store);
        foreach ($paths as $path) {
            $filePath = $path . DS . $localeCode . DS . $fileName;
            if (!empty($filePath) && file_exists($filePath)) {
                return $filePath;
            }
        }
        return null;
    }

    /**
     * @param string|integer|Mage_Core_Model_Store $store (optional)
     *
     * @return array
     */
    public function getLocalePaths($store = null)
    {
        $paths = array();
        $design = $this->getDesign($store);
        $paths[] = Mage::getBaseDir('design') . DS . 'frontend' . DS . $design['package'] . DS . $design['theme'] . DS . 'locale';
        // Check for fallback support
        $fallbackModel = Mage::getModel('core/design_fallback');
        if (!empty($fallbackModel)) {
            $fallbackSchemes = $fallbackModel->getFallbackScheme('frontend', $design['package'], $design['theme']);
            if (!empty($fallbackSchemes)) {
                foreach ($fallbackSchemes as $scheme) {
                    if (!isset($scheme['_package']) || !isset($scheme['_theme'])) continue;
                    $paths[] = Mage::getBaseDir('design') . DS . 'frontend' . DS . $scheme['_package'] . DS . $scheme['_theme'] . DS . 'locale';
                }
            }
        }
        $paths[] = Mage::getBaseDir('design') . DS . 'frontend' . DS . $design['package'] . DS . 'default' . DS . 'locale';
        $paths[] = Mage::getBaseDir('design') . DS . 'frontend' . DS . 'default' . DS . 'default' . DS . 'locale';
        $paths[] = Mage::getBaseDir('design') . DS . 'frontend' . DS . 'base' . DS . 'default' . DS . 'locale';
        $paths[] = Mage::getBaseDir('locale');
        return $paths;
    }

    /**
     * @param string|integer|Mage_Core_Model_Store $store (optional)
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
            if (!empty($store)) $package->setStore($store);
            $package->setArea('frontend');
            $packageName = $package->getPackageName();
            $theme = $package->getTheme('default');
            $package->setArea($originalArea);
            $package->setStore($originalStore);
        }
        if (empty($packageName) || in_array($theme, array('base', 'default'))) {
            $packageName = Mage::getStoreConfig('design/package/name', $store);
        }
        if (empty($theme)) {
            $theme = Mage::getStoreConfig('design/theme/default', $store);
        }
        if (empty($theme) || in_array($theme, array('default'))) {
            $theme = Mage::getStoreConfig('design/theme/locale', $store);
        }
        if (empty($packageName)) {
            $packageName = 'default';
        }
        if (empty($theme)) {
            $theme = 'default';
        }
        return array(
            'package' => $packageName,
            'theme' => $theme,
        );
    }

    public function getPatches()
    {
        return Mage::getModel('ffuenf_devtools/patches')->getPatches();
    }

    /**
     * @return string
     */
    public function getTag()
    {
        $tag = '';
        exec('git describe --abbrev=0 --tags', $tag);
        $this->sHeaderbarTag = array_shift($tag);
        return $this->getStoreConfig(self::CONFIG_EXTENSION_HEADERBAR_TAG, 'sHeaderbarTag');
    }

    /**
     * @return string
     */
    public function getBranch()
    {
        $branch = '';
        exec('git branch', $lines);
        foreach ($lines as $line) {
            if (strpos($line, '*') === 0) {
                $branch = ltrim($line, '* ');
                $this->sHeaderbarBranch = $branch;
                break;
            }
        }
        return $this->getStoreConfig(self::CONFIG_EXTENSION_HEADERBAR_BRANCH, 'sHeaderbarBranch');
    }

    /**
     * @param string $flavour
     * @return string
     */
    public function getCommit($flavour = 'short')
    {
        $hash = '';
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
        $this->sHeaderbarCommit = $hash;
        return $this->getStoreConfig(self::CONFIG_EXTENSION_HEADERBAR_COMMIT, 'sHeaderbarCommit');
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
}