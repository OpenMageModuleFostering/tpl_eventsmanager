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
 * Event - product relation resource model collection
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Model_Resource_Event_Product_Collection extends Mage_Catalog_Model_Resource_Product_Collection
{
    /**
     * remember if fields have been joined
     *
     * @var bool
     */
    protected $_joinedFields = false;

    /**
     * join the link table
     *
     * @access public
     * @return Tpl_EventsManager_Model_Resource_Event_Product_Collection
     * @author TPL
     */
    public function joinFields()
    {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                array('related' => $this->getTable('tpl_eventsmanager/event_product')),
                'related.product_id = e.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    /**
     * add event filter
     *
     * @access public
     * @param Tpl_EventsManager_Model_Event | int $event
     * @return Tpl_EventsManager_Model_Resource_Event_Product_Collection
     * @author TPL
     */
    public function addEventFilter($event)
    {
        if ($event instanceof Tpl_EventsManager_Model_Event) {
            $event = $event->getId();
        }
        if (!$this->_joinedFields ) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.event_id = ?', $event);
        return $this;
    }
}
