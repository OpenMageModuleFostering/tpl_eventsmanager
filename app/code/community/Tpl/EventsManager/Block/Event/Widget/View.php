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
 * Event widget block
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Block_Event_Widget_View extends Mage_Core_Block_Template implements
    Mage_Widget_Block_Interface
{
    protected $_htmlTemplate = 'tpl_eventsmanager/event/widget/view.phtml';

    /**
     * Prepare a for widget
     *
     * @access protected
     * @return Tpl_EventsManager_Block_Event_Widget_View
     * @author TPL
     */
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();
//        $eventId = $this->getData('event_id');
//        if ($eventId) {
//            $event = Mage::getModel('tpl_eventsmanager/event')
//                ->setStoreId(Mage::app()->getStore()->getId())
//                ->load($eventId);
//            if ($event->getStatus()) {
//                $this->setCurrentEvent($event);
//                $this->setTemplate($this->_htmlTemplate);
//            }
//        }
        
         $this->setTemplate($this->_htmlTemplate);
        return $this;
    }
}
