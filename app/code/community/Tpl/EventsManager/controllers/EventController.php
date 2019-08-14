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
 * Event front contrller
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_EventController extends Mage_Core_Controller_Front_Action {

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
        if (Mage::helper('tpl_eventsmanager/event')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                        'home', array(
                    'label' => Mage::helper('tpl_eventsmanager')->__('Home'),
                    'link' => Mage::getUrl(),
                        )
                );
                $breadcrumbBlock->addCrumb(
                        'events', array(
                    'label' => Mage::helper('tpl_eventsmanager')->__('Events'),
                    'link' => '',
                        )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', Mage::helper('tpl_eventsmanager/event')->getEventsUrl());
        }
        if ($headBlock) {
            $headBlock->setTitle(Mage::getStoreConfig('tpl_eventsmanager/event/meta_title'));
            $headBlock->setKeywords(Mage::getStoreConfig('tpl_eventsmanager/event/meta_keywords'));
            $headBlock->setDescription(Mage::getStoreConfig('tpl_eventsmanager/event/meta_description'));
        }
        $this->renderLayout();
    }

    /**
     * init Event
     *
     * @access protected
     * @return Tpl_EventsManager_Model_Event
     * @author TPL
     */
    protected function _initEvent() {
        $eventId = $this->getRequest()->getParam('id', 0);
        $event = Mage::getModel('tpl_eventsmanager/event')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($eventId);
        if (!$event->getId()) {
            return false;
        } elseif (!$event->getStatus()) {
            return false;
        }
        return $event;
    }

    /**
     * view event action
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function viewAction() {
        $event = $this->_initEvent();
        if (!$event) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_event', $event);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('eventsmanager-event eventsmanager-event' . $event->getId());
        }
        if (Mage::helper('tpl_eventsmanager/event')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                        'home', array(
                    'label' => Mage::helper('tpl_eventsmanager')->__('Home'),
                    'link' => Mage::getUrl(),
                        )
                );
                $breadcrumbBlock->addCrumb(
                        'events', array(
                    'label' => Mage::helper('tpl_eventsmanager')->__('Events'),
                    'link' => Mage::helper('tpl_eventsmanager/event')->getEventsUrl(),
                        )
                );
                $breadcrumbBlock->addCrumb(
                        'event', array(
                    'label' => $event->getEventName(),
                    'link' => '',
                        )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', $event->getEventUrl());
        }
        if ($headBlock) {
            if ($event->getMetaTitle()) {
                $headBlock->setTitle($event->getMetaTitle());
            } else {
                $headBlock->setTitle($event->getEventName());
            }
            $headBlock->setKeywords($event->getMetaKeywords());
            $headBlock->setDescription($event->getMetaDescription());
        }
        $this->renderLayout();
    }

    /**
     * events rss list action
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function rssAction() {
        if (Mage::helper('tpl_eventsmanager/event')->isRssEnabled()) {
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
        $event = $this->_initEvent();
        $session = Mage::getSingleton('core/session');
        if ($event) {
            if ($event->getAllowComments()) {
                if ((Mage::getSingleton('customer/session')->isLoggedIn() ||
                        Mage::getStoreConfigFlag('tpl_eventsmanager/event/allow_guest_comment'))) {
                    $comment = Mage::getModel('tpl_eventsmanager/event_comment')->setData($data);
                    $validate = $comment->validate();
                    if ($validate === true) {
                        try {
                            $comment->setEventId($event->getId())
                                    ->setStatus(Tpl_EventsManager_Model_Event_Comment::STATUS_PENDING)
                                    ->setCustomerId(Mage::getSingleton('customer/session')->getCustomerId())
                                    ->setStores(array(Mage::app()->getStore()->getId()))
                                    ->save();
                            $session->addSuccess($this->__('Your comment has been accepted for moderation.'));
                        } catch (Exception $e) {
                            $session->setEventCommentData($data);
                            $session->addError($this->__('Unable to post the comment.'));
                        }
                    } else {
                        $session->setEventCommentData($data);
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
                $session->addError($this->__('This event does not allow comments'));
            }
        }
        $this->_redirectReferer();
    }

    /**
     * For calendar widget
     * @access public
     * @author Shubham
     */
    public function geteventinxmlAction() {
        error_reporting(-1);
        ini_set('display_errors', 'On');
        $event_model = Mage::getModel('tpl_eventsmanager/event');
        $all_events = array();


        $global_level_events_object = $event_model->getGlobalLevelEvents();
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $user_level_events_object = $event_model
                    ->getUserLevelEvents(Mage::getSingleton('customer/session')->getCustomer()->getEmail());
            $all_events = $event_model->mergeEvent($global_level_events_object, $user_level_events_object);
        } else {
            $all_events = $global_level_events_object;
        }
        $global_logged_level_events_object = $event_model->getGlobalLoggedInLevelEvents();

        $all_events = $event_model->mergeEvent($global_logged_level_events_object, $all_events);
        $group_level_events = $event_model->getGroupLevelEvents();
        $all_events = $event_model->mergeEvent($group_level_events, $all_events);



        $xml = new SimpleXMLElement('<xml/>');
        $monthly = $xml->addChild('monthly');


        foreach ($all_events as $event_detail):
            $event_detail->getData();
            $event = $monthly->addChild('event');
            $event->addChild('id', $event_detail->getEntityId());
            $event->addChild('name', $event_detail->getEventName());
            $event->addChild('description', strip_tags(substr($event_detail->getDescription(), 0, 100)) . '....');
            $event->addChild('startdate', $event_detail->getFromDate());
            $event->addChild('enddate', $event_detail->getEndDate());
            $event->addChild('starttime');
            $event->addChild('endtime');
            $event->addChild('color', $event_detail->getColor());
            $event->addChild('url', Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'index.php/'
                    . $event_detail->getUrlKey());

        endforeach;

        Header('Content-type: text/xml');
        print($xml->asXML());
    }

    public function closeNotificationAction() {
        $temp = array();
        $event_id = $this->getRequest()->getParam('eventid');
        array_push($temp, $event_id);
        if (!empty(Mage::getSingleton('core/session')->getData('closedevents'))) {
            $prev_ids = Mage::getSingleton('core/session')->getData('closedevents');

            foreach ($prev_ids as $id):
                array_push($temp, $id);
            endforeach;
        }
        Mage::getSingleton('core/session')->setData('closedevents', array_unique($temp));
        $this->_redirectReferer();
    }

}
