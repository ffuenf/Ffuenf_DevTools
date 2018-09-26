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
 * @copyright  Copyright (c) 2018 ffuenf (http://www.ffuenf.de)
 * @license    http://opensource.org/licenses/mit-license.php MIT License
 */

class Ffuenf_DevTools_Model_System_Config_Source_Datetype
{
    const DATE_CREATED = 'created_at';
    const DATE_UPDATED = 'updated_at';

    public function toOptionArray()
    {
        return array(
            array('value' => self::DATE_CREATED, 'label' => 'Default - Created_At'),
            array('value' => self::DATE_UPDATED, 'label' => 'Updated_At')
        );
    }
}
