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
 * Invitation Status comments controller
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Invitationstatus_Customer_CommentController extends Mage_Core_Controller_Front_Action
{
    /**
     * Action predispatch
     * Check customer authentication for some actions
     *
     * @access public
     * @author TPL
     */
    public function preDispatch()
    {
        parent::preDispatch();
        if (!Mage::getSingleton('customer/session')->authenticate($this)) {
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
        }
    }

    /**
     * List comments
     *
     * @access public
     * @author TPL
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($navigationBlock = $this->getLayout()->getBlock('customer_account_navigation')) {
            $navigationBlock->setActive('tpl_eventsmanager/invitationstatus_customer_comment/');
        }
        if ($block = $this->getLayout()->getBlock('invitationstatus_customer_comment_list')) {
            $block->setRefererUrl($this->_getRefererUrl());
        }

        $this->getLayout()->getBlock('head')->setTitle($this->__('My Invitation Status Comments'));

        $this->renderLayout();
    }

    /**
     * View comment
     *
     * @access public
     * @author TPL
     */
    public function viewAction()
    {
        $commentId = $this->getRequest()->getParam('id');
        $comment = Mage::getModel('tpl_eventsmanager/invitationstatus_comment')->load($commentId);
        if (!$comment->getId() ||
            $comment->getCustomerId() != Mage::getSingleton('customer/session')->getCustomerId() ||
            $comment->getStatus() != Tpl_EventsManager_Model_Invitationstatus_Comment::STATUS_APPROVED) {
            $this->_forward('no-route');
            return;
        }
        $invitationstatus = Mage::getModel('tpl_eventsmanager/invitationstatus')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($comment->getInvitationstatusId());
        if (!$invitationstatus->getId() || $invitationstatus->getStatus() != 1) {
            $this->_forward('no-route');
            return;
        }
        $stores = array(Mage::app()->getStore()->getId(), 0);
        if (count(array_intersect($stores, $comment->getStoreId())) == 0) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_comment', $comment);
        Mage::register('current_invitationstatus', $invitationstatus);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($navigationBlock = $this->getLayout()->getBlock('customer_account_navigation')) {
            $navigationBlock->setActive('tpl_eventsmanager/invitationstatus_customer_comment/');
        }
        if ($block = $this->getLayout()->getBlock('customer_invitationstatus_comment')) {
            $block->setRefererUrl($this->_getRefererUrl());
        }
        $this->getLayout()->getBlock('head')->setTitle($this->__('My Invitation Status Comments'));
        $this->renderLayout();
    }
}
