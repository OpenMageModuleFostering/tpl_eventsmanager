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
 * Event model
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Model_Event extends Mage_Catalog_Model_Abstract {

    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY = 'tpl_eventsmanager_event';
    const CACHE_TAG = 'tpl_eventsmanager_event';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'tpl_eventsmanager_event';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'event';
    protected $_productInstance = null;

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function _construct() {
        parent::_construct();
        $this->_init('tpl_eventsmanager/event');
    }

    /**
     * before save event
     *
     * @access protected
     * @return Tpl_EventsManager_Model_Event
     * @author TPL
     */
    protected function _beforeSave() {
        parent::_beforeSave();

        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * get the url to the event details page
     *
     * @access public
     * @return string
     * @author TPL
     */
    public function getEventUrl() {
        if ($this->getUrlKey()) {
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('tpl_eventsmanager/event/url_prefix')) {
                $urlKey .= $prefix . '/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('tpl_eventsmanager/event/url_suffix')) {
                $urlKey .= '.' . $suffix;
            }
            return Mage::getUrl('', array('_direct' => $urlKey));
        }
        return Mage::getUrl('tpl_eventsmanager/event/view', array('id' => $this->getId()));
    }

    /**
     * check URL key
     *
     * @access public
     * @param string $urlKey
     * @param bool $active
     * @return mixed
     * @author TPL
     */
    public function checkUrlKey($urlKey, $active = true) {
        return $this->_getResource()->checkUrlKey($urlKey, $active);
    }

    /**
     * get the event Description
     *
     * @access public
     * @return string
     * @author TPL
     */
    public function getDescription() {
        $description = $this->getData('description');
        $helper = Mage::helper('cms');
        $processor = $helper->getBlockTemplateProcessor();
        $html = $processor->filter($description);
        return $html;
    }

    /**
     * save event relation
     *
     * @access public
     * @return Tpl_EventsManager_Model_Event
     * @author TPL
     */
    protected function _afterSave() {

        // checking if there exist change in data.. and modules configuration in Admin > System > Configuration
        if ($this->hasDataChanges() && Mage::getStoreConfig('tpl_eventsmanager/customsettings/event_cancel_emails', Mage::app()->getStore())) {
            $newValues = array_diff_assoc($this->getData(), $this->getOrigData());
            $oldValues = array_diff_assoc($this->getOrigData(), $this->getData());
            $added = array_diff_key($this->getData(), $this->getOrigData());
            $unset = array_diff_key($this->getOrigData(), $this->getData());
            //echo $newValues['cancel_event'];
            $mail = "";
            if ($newValues['cancel_event'] == '1') {
                // event is canceled 
                $event_users = Mage::getModel('tpl_eventsmanager/invitationstatus')->getCollection();
                $event_users->addAttributeToFilter('event_id', $this->getEntityId());
                foreach ($event_users as $user) {
                    $email = $user->load()->getCustomerEmail();
                    $name = $user->load()->getCustomerName();
                    $event_name = $user->load()->getEventName();
                    $event_link = $this->getEventUrl();
                    $mail = Mage::helper('tpl_eventsmanager/event')->sendCancelMail($email, $name, 'Cancelled', $event_name, $event_link);
                    try {
                        $mail->send();
                    } catch (Exception $e) {
                        
                    }
                }
            } else if ($newValues['cancel_event'] == '0') {
                // event is reopened
                $event_users = Mage::getModel('tpl_eventsmanager/invitationstatus')->getCollection();
                $event_users->addAttributeToFilter('event_id', $this->getEntityId());
                foreach ($event_users as $user) {
                    $email = $user->load()->getCustomerEmail();
                    $name = $user->load()->getCustomerName();
                    $event_name = $user->load()->getEventName();
                    $event_link = $this->getEventUrl();
                    $mail = Mage::helper('tpl_eventsmanager/event')->sendCancelMail($email, $name, 'Re-Opened', $event_name, $event_link);
                    try {
                        $mail->send();
                    } catch (Exception $e) {
                        
                    }
                }
            }
        }
		 $this->getProductInstance()->saveEventRelation($this);
        return parent::_afterSave();
    }

    /**
     * get product relation model
     *
     * @access public
     * @return Tpl_EventsManager_Model_Event_Product
     * @author TPL
     */
    public function getProductInstance() {
        if (!$this->_productInstance) {
            $this->_productInstance = Mage::getSingleton('tpl_eventsmanager/event_product');
        }
        return $this->_productInstance;
    }

    /**
     * get selected products array
     *
     * @access public
     * @return array
     * @author TPL
     */
    public function getSelectedProducts() {
        if (!$this->hasSelectedProducts()) {
            $products = array();
            foreach ($this->getSelectedProductsCollection() as $product) {
                $products[] = $product;
            }
            $this->setSelectedProducts($products);
        }
        return $this->getData('selected_products');
    }

    /**
     * Retrieve collection selected products
     *
     * @access public
     * @return Tpl_EventsManager_Resource_Event_Product_Collection
     * @author TPL
     */
    public function getSelectedProductsCollection() {
        $collection = $this->getProductInstance()->getProductCollection($this);
        return $collection;
    }

    /**
     * Retrieve default attribute set id
     *
     * @access public
     * @return int
     * @author TPL
     */
    public function getDefaultAttributeSetId() {
        return $this->getResource()->getEntityType()->getDefaultAttributeSetId();
    }

    /**
     * get attribute text value
     *
     * @access public
     * @param $attributeCode
     * @return string
     * @author TPL
     */
    public function getAttributeText($attributeCode) {
        $text = $this->getResource()
                ->getAttribute($attributeCode)
                ->getSource()
                ->getOptionText($this->getData($attributeCode));
        if (is_array($text)) {
            return implode(', ', $text);
        }
        return $text;
    }

    /**
     * check if comments are allowed
     *
     * @access public
     * @return array
     * @author TPL
     */
    public function getAllowComments() {
        if ($this->getData('allow_comment') == Tpl_EventsManager_Model_Adminhtml_Source_Yesnodefault::NO) {
            return false;
        }
        if ($this->getData('allow_comment') == Tpl_EventsManager_Model_Adminhtml_Source_Yesnodefault::YES) {
            return true;
        }
        return Mage::getStoreConfigFlag('tpl_eventsmanager/event/allow_comment');
    }

    /**
     * get default values
     *
     * @access public
     * @return array
     * @author TPL
     */
    public function getDefaultValues() {
        $values = array();
        $values['status'] = 1;
        $values['in_rss'] = 1;
        $values['allow_comment'] = Tpl_EventsManager_Model_Adminhtml_Source_Yesnodefault::USE_DEFAULT;
        return $values;
    }

    /*
     * Written by Pooja Gujarathi
     * Common query for all level events
     */

    protected function getEventsModel() {
        $event_model = $this->getCollection();
        $event_status = $event_model->getResource()->getAttribute("status");
        $Yes = $event_status->getSource()->getOptionId('Yes');
        // $No= $event_status->getSource()->getOptionId('No');
        $event_model->addAttributeToSelect('*');
        $event_model->addAttributeToFilter('status', $Yes);
        return $event_model;
    }

    /*
     * Written by Pooja Gujarathi
     * Returns Global level event collection
     */

    public function getGlobalLevelEvents() {
        $event_model = $this->getEventsModel();
        $event_levelWise = $event_model->getResource()->getAttribute("event_level");
        $global_event = $event_levelWise->getSource()->getOptionId('Global Event');
        $event_model->addAttributeToFilter('event_level', $global_event);
        return $event_model;
    }

    /*
     * Written by Pooja Gujarathi
     * Returns Global logged-in level event collection
     */

    public function getGlobalLoggedInLevelEvents() {
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $event_model = $this->getEventsModel();
            $event_levelWise = $event_model->getResource()->getAttribute("event_level");
            $global_event_loggedIn = $event_levelWise->getSource()->getOptionId('Global Event ( Only visible Logged In Customers )');
            $event_model->addAttributeToFilter('event_level', $global_event_loggedIn);
            return $event_model;
        } else {
            return $this->getCollection()->addFieldToFilter('entity_id', 0);
        }
    }

    /*
     * Written by Pooja Gujarathi
     * Returns User level event collection
     */

    public function getUserLevelEvents($email) {
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $event_model = $this->getEventsModel();
            $event_levelWise = $event_model->getResource()->getAttribute("event_level");
            $userLevel_event = $event_levelWise->getSource()->getOptionId('User Level Event');
            $event_model->addAttributeToFilter('event_level', $userLevel_event);
            $event_model->addAttributeToFilter('user_email', Mage::getSingleton('customer/session')->getCustomer()->getEmail());
            return $event_model;
        } else {
            return $this->getCollection()->addFieldToFilter('entity_id', 0);
        }
    }

    /*
     * Written by Pooja Gujarathi
     * Returns Group level event collection
     */

    public function getGroupLevelEvents() {
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $customer_groupId = Mage::getSingleton('customer/session')->getCustomerGroupId(); //Get Customers Group ID
            $event_model = $this->getEventsModel();
            $event_levelWise = $event_model->getResource()->getAttribute("event_level");
            $groupLevel_event = $event_levelWise->getSource()->getOptionId('Group Level Event');
            $event_model->addAttributeToFilter('event_level', $groupLevel_event);
            $event_model->addAttributeToFilter('group_id', $customer_groupId);
            return $event_model;
        } else {
            return $this->getCollection()->addFieldToFilter('entity_id', 0);
        }
    }

    /*
     * Written by Pooja Gujarathi
     * Returns Merged event collection
     */

    public function mergeEvent($event_collection1, $event_collection2) {

        $merged_ids = array_unique(array_merge($event_collection1->getAllIds(), $event_collection2->getAllIds())); // can sometimes use "getLoadedIds()" as well
        $merged_collection = $this->getCollection()
                ->addAttributeToFilter('entity_id', array('in' => $merged_ids))
                ->addAttributeToSort('from_date', 'desc')
                ->addAttributeToSelect('*');
        return $merged_collection;
    }

    /*

     * Written by shubham
     * returns all events which should be visible (with all permissions checks )
     *  
     *     */

    public function getAllEvents() {
        $all_events = array();


        $global_level_events_object = $this->getGlobalLevelEvents();
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $user_level_events_object = $this
                    ->getUserLevelEvents(Mage::getSingleton('customer/session')->getCustomer()->getEmail());
            $all_events = $this->mergeEvent($global_level_events_object, $user_level_events_object);
        } else {
            $all_events = $global_level_events_object;
        }
        $global_logged_level_events_object = $this->getGlobalLoggedInLevelEvents();

        $all_events = $this->mergeEvent($global_logged_level_events_object, $all_events);
        $group_level_events = $this->getGroupLevelEvents();
        $all_events = $this->mergeEvent($group_level_events, $all_events);

        return $all_events;
    }

}
