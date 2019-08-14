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
 * Event tab on product edit form
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Block_Adminhtml_Catalog_Product_Edit_Tab_Event extends Mage_Adminhtml_Block_Widget_Grid {

    /**
     * Set grid params
     *
     * @access public
     * @author TPL
     */
    public function __construct() {
        parent::__construct();
        $this->setId('event_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        if ($this->getProduct()->getId()) {
            $this->setDefaultFilter(array('in_events' => 1));
        }
    }

    /**
     * prepare the event collection
     *
     * @access protected
     * @return Tpl_EventsManager_Block_Adminhtml_Catalog_Product_Edit_Tab_Event
     * @author TPL
     */
    protected function _prepareCollection() {
        $collection = Mage::getResourceModel('tpl_eventsmanager/event_collection')->addAttributeToSelect('event_name');
        if ($this->getProduct()->getId()) {
            $constraint = 'related.product_id=' . $this->getProduct()->getId();
        } else {
            $constraint = 'related.product_id=0';
        }
        $collection->getSelect()->joinLeft(
                array('related' => $collection->getTable('tpl_eventsmanager/event_product')), 'related.event_id=e.entity_id AND ' . $constraint, array('position')
        );
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * prepare mass action grid
     *
     * @access protected
     * @return Tpl_EventsManager_Block_Adminhtml_Catalog_Product_Edit_Tab_Event
     * @author TPL
     */
    protected function _prepareMassaction() {
        return $this;
    }

    /**
     * prepare the grid columns
     *
     * @access protected
     * @return Tpl_EventsManager_Block_Adminhtml_Catalog_Product_Edit_Tab_Event
     * @author TPL
     */
    protected function _prepareColumns() {
        $this->addColumn(
                'in_events', array(
            'header_css_class' => 'a-center',
            'type' => 'checkbox',
            'name' => 'in_events',
            'values' => $this->_getSelectedEvents(),
            'align' => 'center',
            'index' => 'entity_id'
                )
        );
        $this->addColumn(
                'event_name', array(
            'header' => Mage::helper('tpl_eventsmanager')->__('Event Name'),
            'align' => 'left',
            'index' => 'event_name',
            'renderer' => 'tpl_eventsmanager/adminhtml_helper_column_renderer_relation',
            'params' => array(
                'id' => 'getId'
            ),
            'base_link' => 'adminhtml/eventsmanager_event/edit',
                )
        );
        $this->addColumn(
                'position', array(
            'header' => Mage::helper('tpl_eventsmanager')->__('Position'),
            'name' => 'position',
            'width' => 60,
            'type' => 'number',
            'validate_class' => 'validate-number',
            'index' => 'position',
            'editable' => true,
                )
        );
        return parent::_prepareColumns();
    }

    /**
     * Retrieve selected events
     *
     * @access protected
     * @return array
     * @author TPL
     */
    protected function _getSelectedEvents() {
        $events = $this->getProductEvents();
        if (!is_array($events)) {
            $events = array_keys($this->getSelectedEvents());
        }
        return $events;
    }

    /**
     * Retrieve selected events
     *
     * @access protected
     * @return array
     * @author TPL
     */
    public function getSelectedEvents() {
        $events = array();
        //used helper here in order not to override the product model
        $selected = Mage::helper('tpl_eventsmanager/product')->getSelectedEvents(Mage::registry('current_product'));
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $event) {
            $events[$event->getId()] = array('position' => $event->getPosition());
        }
        return $events;
    }

    /**
     * get row url
     *
     * @access public
     * @param Tpl_EventsManager_Model_Event
     * @return string
     * @author TPL
     */
    public function getRowUrl($item) {
        return '#';
    }

    /**
     * get grid url
     *
     * @access public
     * @return string
     * @author TPL
     */
    public function getGridUrl() {
        return $this->getUrl(
                        '*/*/eventsGrid', array(
                    'id' => $this->getProduct()->getId()
                        )
        );
    }

    /**
     * get the current product
     *
     * @access public
     * @return Mage_Catalog_Model_Product
     * @author TPL
     */
    public function getProduct() {
        return Mage::registry('current_product');
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return Tpl_EventsManager_Block_Adminhtml_Catalog_Product_Edit_Tab_Event
     * @author TPL
     */
    protected function _addColumnFilterToCollection($column) {
        if ($column->getId() == 'in_events') {
            $eventIds = $this->_getSelectedEvents();
            if (empty($eventIds)) {
                $eventIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $eventIds));
            } else {
                if ($eventIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $eventIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

}
