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

class Ffuenf_DevTools_Block_Page_Html_Head extends Mage_Page_Block_Html_Head
{

    /**
     * Set the original module name avoid breaking translations
     */
    public function __construct()
    {
        parent::__construct();
        $this->setModuleName('Mage_Page');
    }

    /**
     * Functionality @see Mage_Page_Block_Html_Head
     * Adds to parameters to influence the ordering
     *
     * @param string $type @see Mage_Page_Block_Html_Head
     * @param string $name @see Mage_Page_Block_Html_Head
     * @param string|null $params @see Mage_Page_Block_Html_Head
     * @param null|string $if @see Mage_Page_Block_Html_Head
     * @param null $cond @see Mage_Page_Block_Html_Head
     * @param string $referenceName name of the item to insert the element before. If name is not found, insert at the end, * has special meaning (before all / before all)
     * @param bool $before If true insert before the $referenceName instead of after
     * @return Ffuenf_DevTools_Block_Page_Html_Head
     */
    public function addItem($type, $name, $params = null, $if = null, $cond = null, $referenceName = "*", $before = false)
    {
        // allow skipping of parameters in the layout XML files via empty-string
        if ($params == '') {
            $params = null;
        }
        if ($if == '') {
            $if = null;
        }
        if ($cond == '') {
            $cond = null;
        }
        parent::addItem($type, $name, $params, $if, $cond);
        // that is the standard behaviour
        if ($referenceName == '*' && $before === false) {
            return $this;
        }
        $this->_sortItems($referenceName, $before, $type);
        return $this;
    }

    /**
     * @param string $referenceName
     * @param string $before
     * @param string $type
     */
    protected function _sortItems($referenceName, $before, $type)
    {
        $items = $this->_data['items'];
        // get newly inserted item so we do not have to reproduce the functionality of the parent
        end($items);
        $newKey = key($items);
        $newVal = array_pop($items);
        $newItems = array();
        if ($referenceName == '*' and $before == true) {
            $newItems[$newKey] = $newVal;
        }
        $referenceName = $type . '/' . $referenceName;
        foreach ($items as $key => $value) {
            if ($key == $referenceName && $before == true) {
                $newItems[$newKey] = $newVal;
            }
            $newItems[$key] = $value;
            if ($key == $referenceName && $before == false) {
                $newItems[$newKey] = $newVal;
            }
        }
        // replace items only if the reference was found (otherwise insert as last item)
        if (isset($newItems[$newKey])) {
            $this->_data['items'] = $newItems;
        }
    }

    /**
     * Functionality @see Mage_Page_Block_Html_Head
     * Adds to parameters to influence the ordering @see addItem
     *
     * @param string $name @see Mage_Page_Block_Html_Head
     * @param string $params @see Mage_Page_Block_Html_Head
     * @param $referenceName @see addItem
     * @param boolean|null $before @see addItem
     * @return Ffuenf_DevTools_Block_Page_Html_Head
     */
    public function addCss($name, $params = "", $referenceName = "*", $before = null)
    {
        $this->addItem('skin_css', $name, $params, null, null, $referenceName, $before);
        return $this;
    }

    /**
     * Functionality @see Mage_Page_Block_Html_Head
     * Adds to parameters to influence the ordering @see addItem
     *
     * @param string $name @see Mage_Page_Block_Html_Head
     * @param string $params @see Mage_Page_Block_Html_Head
     * @param $referenceName @see addItem
     * @param boolean|null $before @see addItem
     * @return Ffuenf_DevTools_Block_Page_Html_Head
     */
    public function addCssIe($name, $params = "", $referenceName = "*", $before = null)
    {
        $this->addItem('skin_css', $name, $params, 'IE', null, $referenceName, $before);
        return $this;
    }

    /**
     * Functionality @see Mage_Page_Block_Html_Head
     * Adds to parameters to influence the ordering @see addItem
     *
     * @param string $name @see Mage_Page_Block_Html_Head
     * @param string $params @see Mage_Page_Block_Html_Head
     * @param $referenceName @see addItem
     * @param boolean|null $before @see addItem
     * @return Ffuenf_DevTools_Block_Page_Html_Head
     */
    public function addJs($name, $params = "", $referenceName = "*", $before = null)
    {
        $this->addItem('js', $name, $params, null, null, $referenceName, $before);
        return $this;
    }

    /**
     * Functionality @see Mage_Page_Block_Html_Head
     * Adds to parameters to influence the ordering @see addItem
     *
     * @param string $name @see Mage_Page_Block_Html_Head
     * @param string $params @see Mage_Page_Block_Html_Head
     * @param $referenceName @see addItem
     * @param boolean|null $before @see addItem
     * @return Ffuenf_DevTools_Block_Page_Html_Head
     */
    public function addJsIe($name, $params = "", $referenceName = "*", $before = null)
    {
        $this->addItem('js', $name, $params, 'IE', null, $referenceName, $before);
        return $this;
    }

    /**
     * Add HEAD External Item
     *
     * Allowed types:
     *  - js
     *  - js_css
     *  - skin_js
     *  - skin_css
     *  - rss
     *
     * @param string $type
     * @param string $name
     * @param string $params
     * @param string $if
     * @param string $cond
     * @return Mage_Page_Block_Html_Head
     */
    public function addExternalItem($type, $name, $params=null, $if=null, $cond=null)
    {
        parent::addItem($type, $name, $params=null, $if=null, $cond=null);
    }

    /**
     * Remove External Item from HEAD entity
     *
     * @param string $type
     * @param string $name
     * @return Mage_Page_Block_Html_Head
     */
    public function removeExternalItem($type, $name)
    {
        parent::removeItem($type, $name);
    }

    /**
     * Classify HTML head item and queue it into "lines" array
     *
     * @see self::getCssJsHtml()
     * @param array &$lines
     * @param string $itemIf
     * @param string $itemType
     * @param string $itemParams
     * @param string $itemName
     * @param array $itemThe
     */
    protected function _separateOtherHtmlHeadElements(&$lines, $itemIf, $itemType, $itemParams, $itemName, $itemThe)
    {
        $params = $itemParams ? ' ' . $itemParams : '';
        $href   = $itemName;
        switch ($itemType) {
            case 'rss':
                $lines[$itemIf]['other'][] = sprintf('<link href="%s"%s rel="alternate" type="application/rss+xml" />',
                    $href, $params
                );
                break;
            case 'link_rel':
                $lines[$itemIf]['other'][] = sprintf('<link%s href="%s" />', $params, $href);
                break;
            
           	case 'external_js':
                $lines[$itemIf]['other'][] = sprintf('<script type="text/javascript" src="%s" %s></script>', $href, $params);
                break;
                            
          	case 'external_css':
                $lines[$itemIf]['other'][] = sprintf('<link rel="stylesheet" type="text/css" href="%s" %s/>', $href, $params);
                break;
        }
    }
}
