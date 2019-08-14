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
 * Invitation Status list block
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author TPL
 */
class Tpl_EventsManager_Block_Invitationstatus_List extends Mage_Core_Block_Template
{
    /**
     * initialize
     *
     * @access public
     * @author TPL
     */
    public function _construct()
    {
        parent::_construct();
        $invitationsstatus = Mage::getResourceModel('tpl_eventsmanager/invitationstatus_collection')
                         ->setStoreId(Mage::app()->getStore()->getId())
                         ->addAttributeToSelect('*')
                         ->addAttributeToFilter('status', 1);
        $invitationsstatus->setOrder('event_name', 'asc');
        $this->setInvitationsstatus($invitationsstatus);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Tpl_EventsManager_Block_Invitationstatus_List
     * @author TPL
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
            'page/html_pager',
            'tpl_eventsmanager.invitationstatus.html.pager'
        )
        ->setCollection($this->getInvitationsstatus());
        $this->setChild('pager', $pager);
        $this->getInvitationsstatus()->load();
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
}
