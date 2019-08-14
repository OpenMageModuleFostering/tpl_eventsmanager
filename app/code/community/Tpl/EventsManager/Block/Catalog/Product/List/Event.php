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
 * Event list on product page block
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Block_Catalog_Product_List_Event extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * get the list of events
     *
     * @access protected
     * @return Tpl_EventsManager_Model_Resource_Event_Collection
     * @author TPL
     */
    public function getEventCollection()
    {
        if (!$this->hasData('event_collection')) {
            $product = Mage::registry('product');
//            $collection = Mage::getResourceSingleton('tpl_eventsmanager/event_collection')
            $collection = Mage::getModel('tpl_eventsmanager/event')->getAllEvents();
        
        
               $collection->setStoreId(Mage::app()->getStore()->getId())
                ->addAttributeToSelect('event_name', 1)
                ->addAttributeToFilter('status', 1)
                ->addProductFilter($product);
            $collection->getSelect()->order('related_product.position', 'ASC');
            $this->setData('event_collection', $collection);
        }
        return $this->getData('event_collection');
    }
}
