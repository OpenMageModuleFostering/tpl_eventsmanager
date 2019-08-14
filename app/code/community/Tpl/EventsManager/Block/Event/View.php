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
 * Event view block
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Block_Event_View extends Mage_Core_Block_Template {

    /**
     * get the current event
     *
     * @access public
     * @return mixed (Tpl_EventsManager_Model_Event|null)
     * @author TPL
     */
    /* Gives related products to event 

     * Added by Shubham     */
    public function getProductCollection() {
        $collection = $this->getCurrentEvent()->getSelectedProductsCollection();
        $collection->addAttributeToSelect('name');
        $collection->addUrlRewrite();
        $collection->getSelect()->order('related.position');
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
        return $collection;
    }

    public function getCurrentEvent() {
        return Mage::helper('tpl_eventsmanager/event')->validateCurrentEvent();
    }

}
