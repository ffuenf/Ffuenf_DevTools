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

class Ffuenf_DevTools_Model_Patches extends Mage_Core_Model_Abstract
{
    public $appliedPatches = array();
    private $patchFile;

    protected function _construct()
    {
        $this->patchFile = Mage::getBaseDir('etc') . DS . 'applied.patches.list';
        $this->_loadPatchFile();
    }

    public function getPatches()
    {
        return implode(', ',$this->appliedPatches);
    }

    protected function _loadPatchFile()
    {
        $ioAdapter = new Varien_Io_File();
        if (!$ioAdapter->fileExists($this->patchFile)) {
            return;
        }
        $ioAdapter->open(array('path' => $ioAdapter->dirname($this->patchFile)));
        $ioAdapter->streamOpen($this->patchFile, 'r');
        while ($buffer = $ioAdapter->streamRead()) {
            if(stristr($buffer,'|')){
                list($date, $patch) = array_map('trim', explode('|', $buffer));
                $this->appliedPatches[] = $patch;
            }
        }
        $ioAdapter->streamClose();
    }
}