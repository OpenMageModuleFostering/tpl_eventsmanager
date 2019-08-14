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
 * Invitation Status front contrller
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_InvitationstatusController extends Mage_Core_Controller_Front_Action {

    /**
     * default action
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function indexAction() {
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if (Mage::helper('tpl_eventsmanager/invitationstatus')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                        'home', array(
                    'label' => Mage::helper('tpl_eventsmanager')->__('Home'),
                    'link' => Mage::getUrl(),
                        )
                );
                $breadcrumbBlock->addCrumb(
                        'invitationsstatus', array(
                    'label' => Mage::helper('tpl_eventsmanager')->__('Invitations Status'),
                    'link' => '',
                        )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', Mage::helper('tpl_eventsmanager/invitationstatus')->getInvitationsstatusUrl());
        }
        if ($headBlock) {
            $headBlock->setTitle(Mage::getStoreConfig('tpl_eventsmanager/invitationstatus/meta_title'));
            $headBlock->setKeywords(Mage::getStoreConfig('tpl_eventsmanager/invitationstatus/meta_keywords'));
            $headBlock->setDescription(Mage::getStoreConfig('tpl_eventsmanager/invitationstatus/meta_description'));
        }
        $this->renderLayout();
    }

    /**
     * init Invitation Status
     *
     * @access protected
     * @return Tpl_EventsManager_Model_Invitationstatus
     * @author TPL
     */
    protected function _initInvitationstatus() {
        $invitationstatusId = $this->getRequest()->getParam('id', 0);
        $invitationstatus = Mage::getModel('tpl_eventsmanager/invitationstatus')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($invitationstatusId);
        if (!$invitationstatus->getId()) {
            return false;
        } elseif (!$invitationstatus->getStatus()) {
            return false;
        }
        return $invitationstatus;
    }

    /**
     * view invitation status action
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function viewAction() {
        $invitationstatus = $this->_initInvitationstatus();
        if (!$invitationstatus) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_invitationstatus', $invitationstatus);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('eventsmanager-invitationstatus eventsmanager-invitationstatus' . $invitationstatus->getId());
        }
        if (Mage::helper('tpl_eventsmanager/invitationstatus')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                        'home', array(
                    'label' => Mage::helper('tpl_eventsmanager')->__('Home'),
                    'link' => Mage::getUrl(),
                        )
                );
                $breadcrumbBlock->addCrumb(
                        'invitationsstatus', array(
                    'label' => Mage::helper('tpl_eventsmanager')->__('Invitations Status'),
                    'link' => Mage::helper('tpl_eventsmanager/invitationstatus')->getInvitationsstatusUrl(),
                        )
                );
                $breadcrumbBlock->addCrumb(
                        'invitationstatus', array(
                    'label' => $invitationstatus->getEventName(),
                    'link' => '',
                        )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', $invitationstatus->getInvitationstatusUrl());
        }
        if ($headBlock) {
            if ($invitationstatus->getMetaTitle()) {
                $headBlock->setTitle($invitationstatus->getMetaTitle());
            } else {
                $headBlock->setTitle($invitationstatus->getEventName());
            }
            $headBlock->setKeywords($invitationstatus->getMetaKeywords());
            $headBlock->setDescription($invitationstatus->getMetaDescription());
        }
        $this->renderLayout();
    }

    /**
     * invitations status rss list action
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function rssAction() {
        if (Mage::helper('tpl_eventsmanager/invitationstatus')->isRssEnabled()) {
            $this->getResponse()->setHeader('Content-type', 'text/xml; charset=UTF-8');
            $this->loadLayout(false);
            $this->renderLayout();
        } else {
            $this->getResponse()->setHeader('HTTP/1.1', '404 Not Found');
            $this->getResponse()->setHeader('Status', '404 File not found');
            $this->_forward('nofeed', 'index', 'rss');
        }
    }

    /**
     * Submit new comment action
     * @access public
     * @author TPL
     */
    public function commentpostAction() {
        $data = $this->getRequest()->getPost();
        $invitationstatus = $this->_initInvitationstatus();
        $session = Mage::getSingleton('core/session');
        if ($invitationstatus) {
            if ($invitationstatus->getAllowComments()) {
                if ((Mage::getSingleton('customer/session')->isLoggedIn() ||
                        Mage::getStoreConfigFlag('tpl_eventsmanager/invitationstatus/allow_guest_comment'))) {
                    $comment = Mage::getModel('tpl_eventsmanager/invitationstatus_comment')->setData($data);
                    $validate = $comment->validate();
                    if ($validate === true) {
                        try {
                            $comment->setInvitationstatusId($invitationstatus->getId())
                                    ->setStatus(Tpl_EventsManager_Model_Invitationstatus_Comment::STATUS_PENDING)
                                    ->setCustomerId(Mage::getSingleton('customer/session')->getCustomerId())
                                    ->setStores(array(Mage::app()->getStore()->getId()))
                                    ->save();
                            $session->addSuccess($this->__('Your comment has been accepted for moderation.'));
                        } catch (Exception $e) {
                            $session->setInvitationstatusCommentData($data);
                            $session->addError($this->__('Unable to post the comment.'));
                        }
                    } else {
                        $session->setInvitationstatusCommentData($data);
                        if (is_array($validate)) {
                            foreach ($validate as $errorMessage) {
                                $session->addError($errorMessage);
                            }
                        } else {
                            $session->addError($this->__('Unable to post the comment.'));
                        }
                    }
                } else {
                    $session->addError($this->__('Guest comments are not allowed'));
                }
            } else {
                $session->addError($this->__('This invitation status does not allow comments'));
            }
        }
        $this->_redirectReferer();
    }

    // shubham 
    public function invitationresponseAction() {
        $session = Mage::getSingleton('core/session');
        $message = "";

        if ($this->getRequest()->getParam('status') && $this->getRequest()->getParam('event_id') && $this->getRequest()->getParam('event_name')) {

            if (Mage::getSingleton('customer/session')->isLoggedIn()) {
                $status = '';
                $customer = Mage::getSingleton('customer/session')->getCustomer();
                $invitationstatus_model = Mage::getModel('tpl_eventsmanager/invitationstatus')->getCollection();
                $event_invitation_status = $invitationstatus_model->getResource()->getAttribute("event_invitation_status");

                $yes = $event_invitation_status->getSource()->getOptionId('Accepted');
                $no = $event_invitation_status->getSource()->getOptionId('Rejected');
                if ( $this->getRequest()->getParam('status') == '1') {
                    $status = $yes;
                } else {
                    $status = $no;
                }

                $data = $invitationstatus_model
                        ->addAttributeToFilter('customer_email', $customer->getEmail())
                        ->addAttributeToFilter('event_id', $this->getRequest()->getParam('event_id'))
                        ->getData();
            
                // if customer entry is already in database
                if (count($data)) {
                    
                    $existing_invitationstatus = Mage::getModel('tpl_eventsmanager/invitationstatus')->load($data[0]['entity_id']);
                    $existing_invitationstatus->setData('event_invitation_status', $status)->getResource()
                            ->saveAttribute($existing_invitationstatus, 'event_invitation_status');
                    ;
                    $existing_invitationstatus->save();

                    $message = 'Invitation Response updated successfully';
                    $session->addSuccess($message);
                    
                } else {

                    $invitationstatus = Mage::getModel('tpl_eventsmanager/invitationstatus');
                    $invitationstatusData = array(
                        'event_id' => $this->getRequest()->getParam('event_id'),
                        'event_name' => $this->getRequest()->getParam('event_name'),
                        'customer_name' => $customer->getName(),
                        'customer_email' => $customer->getEmail(),
                        'customer_address' => $customer->getPrimaryBillingAddress(),
                        'customer_contact_number' => '', // remove this attribute
                        'event_invitation_status' => $status,
                        'status' => '1',
                        'url_key' => '',
                        'in_rss' => '',
                        'allow_comment' => '',
                        'meta_title' => '',
                        'meta_keywords' => '',
                        'meta_description' => ''
                    );


//                    echo "<pre>";
//                    print_r($invitationstatusData);
                    
                    $invitationstatus->addData($invitationstatusData);
                    $invitationstatus->setAttributeSetId($invitationstatus->getDefaultAttributeSetId());

                    try {
                        $invitationstatus->save();
                        $invitationstatusId = $invitationstatus->getId();
                        $message = 'Invitation Response is saved successfully';
                        $session->addSuccess($message);
                        
                    } catch (Exception $e) {
                        Mage::logException($e);
                        $session->addError($this->__('Unable to save your response'));
                    }
                }
            } else {
                $session->addError($this->__('Please login to record your invitation response'));
            }
        }
        
        $this->_redirectReferer();
    }

}
