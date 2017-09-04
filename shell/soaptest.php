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
 * @copyright  Copyright (c) 2017 ffuenf (http://www.ffuenf.de)
 * @license    http://opensource.org/licenses/mit-license.php MIT License
 */

require_once 'abstract.php';

class Soap_Test extends Mage_Shell_Abstract
{
    const API_URL            = "";
    const API_USER           = "";
    const API_KEY            = "";
    const ORDER_INCREMENT_ID = "";

    public function run()
    {
        $api       = new SoapClient(self::API_URL . '/api/v2_soap?wsdl=1');
        $sessionId = $api->login(self::API_USER, self::API_KEY);
        echo "\n";
        echo "Sales Order Info: increment_id:", self::ORDER_INCREMENT_ID;
        echo "\n";
        echo "----------------------------------------------------------------------------------------";
        echo "\n";
        $result = $api->salesOrderInfo($sessionId, self::ORDER_INCREMENT_ID);
        var_dump($result);
    }
}
$shell = new Soap_Test();
$shell->run();