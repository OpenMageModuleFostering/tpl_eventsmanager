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
 * Event comment list block
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Block_Event_Comment_List extends Mage_Core_Block_Template
{
    /**
     * initialize
     *
     * @access public
     * @author TPL
     */
    public function __construct()
    {
        parent::__construct();
        $event = $this->getEvent();
        $comments = Mage::getResourceModel('tpl_eventsmanager/event_comment_collection')
            ->addFieldToFilter('event_id', $event->getId())
            ->addStoreFilter(Mage::app()->getStore())
             ->addFieldToFilter('status', 1);
        $comments->setOrder('created_at', 'asc');
        $this->setComments($comments);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Tpl_EventsManager_Block_Event_Comment_List
     * @author TPL
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
            'page/html_pager',
            'tpl_eventsmanager.event.html.pager'
        )
        ->setCollection($this->getComments());
        $this->setChild('pager', $pager);
        $this->getComments()->load();
        return $this;
    }

    /**
     * get the pager html
     *
     * @access public
     * @return string
     * @author TPL
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
    /**
     * get the current event
     *
     * @access protected
     * @return Tpl_EventsManager_Model_Event
     * @author TPL
     */
    public function getEvent()
    {
        return Mage::registry('current_event');
    }
}
