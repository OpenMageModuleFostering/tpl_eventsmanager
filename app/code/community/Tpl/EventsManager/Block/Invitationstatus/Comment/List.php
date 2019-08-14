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
 * Invitation Status comment list block
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author TPL
 */
class Tpl_EventsManager_Block_Invitationstatus_Comment_List extends Mage_Core_Block_Template
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
        $invitationstatus = $this->getInvitationstatus();
        $comments = Mage::getResourceModel('tpl_eventsmanager/invitationstatus_comment_collection')
            ->addFieldToFilter('invitationstatus_id', $invitationstatus->getId())
            ->addStoreFilter(Mage::app()->getStore())
             ->addFieldToFilter('status', 1);
        $comments->setOrder('created_at', 'asc');
        $this->setComments($comments);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Tpl_EventsManager_Block_Invitationstatus_Comment_List
     * @author TPL
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
            'page/html_pager',
            'tpl_eventsmanager.invitationstatus.html.pager'
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
     * get the current invitation status
     *
     * @access protected
     * @return Tpl_EventsManager_Model_Invitationstatus
     * @author TPL
     */
    public function getInvitationstatus()
    {
        return Mage::registry('current_invitationstatus');
    }
}
