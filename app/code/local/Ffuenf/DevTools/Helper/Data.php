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

    /**
     * Path for ffuenf_devtools/general/enable
     */
    const CONFIG_EXTENSION_ACTIVE = 'ffuenf_devtools/general/enable';

    /**
     * Path for ffuenf_devtools/n98magerun/path
     */
    const CONFIG_EXTENSION_N98MAGERUNPATH = 'ffuenf_devtools/n98magerun/path';

    /**
     * Path for ffuenf_devtools/backup/dump_database_script_path
     */
    const CONFIG_EXTENSION_BACKUP_DUMPDATABASESCRIPTPATH = 'ffuenf_devtools/backup/dump_database_script_path';

    /**
     * Variable for if the extension is active
     *
     * @var bool
     */
    protected $bExtensionActive;

    /**
     * Variable for the n98-magerun system path
     *
     * @var string
     */
    protected $sN98MagerunPath;

    /**
     * Variable for the path to the dump_database shell script
     *
     * @var string
     */
    protected $sDumpDatabaseScriptPath;

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
     * Variable for if n98-magerun is available
     *
     * @var bool
     */
    protected $bN98MagerunAvailable;

    /**
     * Checks if n98-magerun is available
     *
     * @return bool
     */
    public function isN98MagerunAvailable()
    {
        $output = $this->runN98Magerun(array('--version'));
        if (!isset($output[0]) || strpos($output[0], 'n98-magerun version') === false) {
            $this->bN98MagerunAvailable = true;
        } else {
            $this->bN98MagerunAvailable = false;
        }
        return $this->bN98MagerunAvailable;
    }

    /**
     * Checks if n98-magerun is present and returns the version number
     *
     * @return string
     * @throws Mage_Core_Exception
     */
    public function checkN98Magerun()
    {
        $output = $this->runN98Magerun(array('--version'));
        if (!$this->getN98MagerunPath()) {
            Mage::throwException('No valid n98-magerun found');
        }
        $matches = array();
        preg_match('/(\d+\.\d+\.\d)/', $output[0], $matches);
        return $matches[1];
    }

    /**
     * Get n98-magerun path
     *
     * @return string
     * @throws Mage_Core_Exception
     */
    public function getN98MagerunPath()
    {
        $baseDir = Mage::getBaseDir();
        $path = $baseDir . DS . $this->getStoreConfig(self::CONFIG_EXTENSION_N98MAGERUNPATH, 'sN98MagerunPath');
        if (!is_file($path)) {
            Mage::throwException('Could not find n98-magerun at ' . $path);
        }
        return $path;
    }

    /**
     * Run n98-magerun command
     *
     * @param string[]
     */
    public function runN98Magerun($options = array())
    {
        array_unshift($options, '--root-dir='.Mage::getBaseDir());
        array_unshift($options, '--no-interaction');
        array_unshift($options, '--no-ansi');
        $output = array();
        exec('php -d ' . $this->getN98MagerunPath() . ' ' . implode(' ', $options), $output);
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
}