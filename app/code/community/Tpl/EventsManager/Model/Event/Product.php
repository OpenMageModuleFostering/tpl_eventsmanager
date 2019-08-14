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
 * Event product model
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Model_Event_Product extends Mage_Core_Model_Abstract
{
    /**
     * Initialize resource
     *
     * @access protected
     * @return void
     * @author TPL
     */
    protected function _construct()
    {
        $this->_init('tpl_eventsmanager/event_product');
    }

    /**
     * Save data for event-product relation
     * @access public
     * @param  Tpl_EventsManager_Model_Event $event
     * @return Tpl_EventsManager_Model_Event_Product
     * @author TPL
     */
    public function saveEventRelation($event)
    {
        $data = $event->getProductsData();
        if (!is_null($data)) {
            $this->_getResource()->saveEventRelation($event, $data);
        }
        return $this;
    }

    /**
     * get products for event
     *
     * @access public
     * @param Tpl_EventsManager_Model_Event $event
     * @return Tpl_EventsManager_Model_Resource_Event_Product_Collection
     * @author TPL
     */
    public function getProductCollection($event)
    {
        $collection = Mage::getResourceModel('tpl_eventsmanager/event_product_collection')
            ->addEventFilter($event);
        return $collection;
    }
}
