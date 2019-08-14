<?php
/**
 * Tpl_EventsManager extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Tpl
 * @package        Tpl_EventsManager
 * @copyright      Copyright (c) 2016
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * EventsManager default helper
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * convert array to options
     *
     * @access public
     * @param $options
     * @return array
     * @author TPL
     */
    public function convertOptions($options)
    {
        $converted = array();
        foreach ($options as $option) {
            if (isset($option['value']) && !is_array($option['value']) &&
                isset($option['label']) && !is_array($option['label'])) {
                $converted[$option['value']] = $option['label'];
            }
        }
        return $converted;
    }
    
    
    /* function to check if there already exist bootstrap if not the return file path */
    public function checkBootstrapExist()
    {
        $layout = Mage::app()->getLayout();
        $head= $layout->getBlock('head');
        $jsget= $head->getCssJsHtml();//all magento js/css store in $jsget variable
        //echo $jsget;
        $bootstrap= strpos($jsget, 'bootstrap.css');
        if(!$bootstrap){
            return 'css/tpl_eventsmanager/bootstrap/css/bootstrap.css';
        }
        else{
            return '';
        }
    
    }
}
