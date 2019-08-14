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
 * Event collection resource model
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Model_Resource_Event_Collection extends Mage_Catalog_Model_Resource_Collection_Abstract
{
    protected $_joinedFields = array();

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author TPL
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('tpl_eventsmanager/event');
    }

    /**
     * get events as array
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @param array $additional
     * @return array
     * @author TPL
     */
    protected function _toOptionArray($valueField='entity_id', $labelField='event_name', $additional=array())
    {
        $this->addAttributeToSelect('event_name');
        return parent::_toOptionArray($valueField, $labelField, $additional);
    }

    /**
     * get options hash
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @return array
     * @author TPL
     */
    protected function _toOptionHash($valueField='entity_id', $labelField='event_name')
    {
        $this->addAttributeToSelect('event_name');
        return parent::_toOptionHash($valueField, $labelField);
    }

    /**
     * add the product filter to collection
     *
     * @access public
     * @param mixed (Mage_Catalog_Model_Product|int) $product
     * @return Tpl_EventsManager_Model_Resource_Event_Collection
     * @author TPL
     */
    public function addProductFilter($product)
    {
        if ($product instanceof Mage_Catalog_Model_Product) {
            $product = $product->getId();
        }
        if (!isset($this->_joinedFields['product'])) {
            $this->getSelect()->join(
                array('related_product' => $this->getTable('tpl_eventsmanager/event_product')),
                'related_product.event_id = e.entity_id',
                array('position')
            );
            $this->getSelect()->where('related_product.product_id = ?', $product);
            $this->_joinedFields['product'] = true;
        }
        return $this;
    }

    /**
     * Get SQL for get record count.
     * Extra GROUP BY strip added.
     *
     * @access public
     * @return Varien_Db_Select
     * @author TPL
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(Zend_Db_Select::GROUP);
        return $countSelect;
    }
}
