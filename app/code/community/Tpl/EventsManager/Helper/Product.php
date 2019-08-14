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
 * Product helper
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Helper_Product extends Tpl_EventsManager_Helper_Data
{

    /**
     * get the selected events for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return array()
     * @author TPL
     */
    public function getSelectedEvents(Mage_Catalog_Model_Product $product)
    {
        if (!$product->hasSelectedEvents()) {
            $events = array();
            foreach ($this->getSelectedEventsCollection($product) as $event) {
                $events[] = $event;
            }
            $product->setSelectedEvents($events);
        }
        return $product->getData('selected_events');
    }

    /**
     * get event collection for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return Tpl_EventsManager_Model_Resource_Event_Collection
     * @author TPL
     */
    public function getSelectedEventsCollection(Mage_Catalog_Model_Product $product)
    {
        $collection = Mage::getResourceSingleton('tpl_eventsmanager/event_collection')
            ->addProductFilter($product);
        return $collection;
    }
}
