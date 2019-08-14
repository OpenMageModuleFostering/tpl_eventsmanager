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
 * Event link widget block
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Block_Event_Widget_Link extends Tpl_EventsManager_Block_Event_Widget_View
{
    protected $_htmlTemplate = 'tpl_eventsmanager/event/widget/link.phtml';


        // added by shubham 
        // only permissible events should be visible under listing page
        // rather than all events

    public function _construct()
    {
        parent::_construct();
        $current_date = date('Y-m-d 00:00:00');
        $events = Mage::getModel('tpl_eventsmanager/event')->getAllEvents();
        $events->addAttributeToFilter('from_date', array('gt' => $current_date));   
        $this->setEvents($events);
    }
}
