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
 * Adminhtml observer
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Model_Adminhtml_Observer
{
    /**
     * check if tab can be added
     *
     * @access protected
     * @param Mage_Catalog_Model_Product $product
     * @return bool
     * @author TPL
     */
    protected function _canAddTab($product)
    {
        if ($product->getId()) {
            return true;
        }
        if (!$product->getAttributeSetId()) {
            return false;
        }
        $request = Mage::app()->getRequest();
        if ($request->getParam('type') == 'configurable') {
            if ($request->getParam('attributes')) {
                return true;
            }
        }
        return false;
    }

    /**
     * add the event tab to products
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Tpl_EventsManager_Model_Adminhtml_Observer
     * @author TPL
     */
    public function addProductEventBlock($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $product = Mage::registry('product');
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs && $this->_canAddTab($product)) {
            $block->addTab(
                'events',
                array(
                    'label' => Mage::helper('tpl_eventsmanager')->__('Events'),
                    'url'   => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/eventsmanager_event_catalog_product/events',
                        array('_current' => true)
                    ),
                    'class' => 'ajax',
                )
            );
        }
        return $this;
    }

    /**
     * save event - product relation
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Tpl_EventsManager_Model_Adminhtml_Observer
     * @author TPL
     */
    public function saveProductEventData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('events', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $product = Mage::registry('product');
            $eventProduct = Mage::getResourceSingleton('tpl_eventsmanager/event_product')
                ->saveProductRelation($product, $post);
        }
        return $this;
    }}
