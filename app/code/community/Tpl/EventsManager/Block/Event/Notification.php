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
class Tpl_EventsManager_Block_Event_Notification extends Mage_Core_Block_Template
{
    /**
     * get the current event
     *
     * @access public
     * @return mixed (Tpl_EventsManager_Model_Event|null)
     * @author TPL
     */
    
     public function _construct()
    {
        parent::_construct();
       
        // added by shubham 
        // only permissible notification events should be visible 
        // rather than all events
        $event_model= Mage::getModel('tpl_eventsmanager/event');
        $event_typeWise = $event_model->getResource()->getAttribute("event_type");
        $notification_event = $event_typeWise->getSource()->getOptionId('Notification Event');
        
        
        $events = $event_model->getAllEvents();
        
        $events->addAttributeToFilter('event_type', $notification_event);
        
        $events->setOrder('event_name', 'asc');
        $this->setEvents($events);
    }
    
    
}
