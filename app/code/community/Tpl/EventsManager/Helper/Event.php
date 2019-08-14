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
 * Event helper
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Helper_Event extends Mage_Core_Helper_Abstract {

    /**
     * get the url to the events list page
     *
     * @access public
     * @return string
     * @author TPL
     */
    public function getEventsUrl() {
        if ($listKey = Mage::getStoreConfig('tpl_eventsmanager/event/url_rewrite_list')) {
            return Mage::getUrl('', array('_direct' => $listKey));
        }
        return Mage::getUrl('tpl_eventsmanager/event/index');
    }

    /**
     * check if breadcrumbs can be used
     *
     * @access public
     * @return bool
     * @author TPL
     */
    public function getUseBreadcrumbs() {
        return Mage::getStoreConfigFlag('tpl_eventsmanager/event/breadcrumbs');
    }

    /**
     * check if the rss for event is enabled
     *
     * @access public
     * @return bool
     * @author TPL
     */
    public function isRssEnabled() {
        return Mage::getStoreConfigFlag('rss/config/active') &&
                Mage::getStoreConfigFlag('tpl_eventsmanager/event/rss');
    }

    /**
     * get the link to the event rss list
     *
     * @access public
     * @return string
     * @author TPL
     */
    public function getRssUrl() {
        return Mage::getUrl('tpl_eventsmanager/event/rss');
    }

    /**
     * get base files dir
     *
     * @access public
     * @return string
     * @author TPL
     */
    public function getFileBaseDir() {
        return Mage::getBaseDir('media') . DS . 'event' . DS . 'file';
    }

    /**
     * get base file url
     *
     * @access public
     * @return string
     * @author TPL
     */
    public function getFileBaseUrl() {
        return Mage::getBaseUrl('media') . 'event' . '/' . 'file';
    }

    /**
     * get event attribute source model
     *
     * @access public
     * @param string $inputType
     * @return mixed (string|null)
     * @author TPL
     */
    public function getAttributeSourceModelByInputType($inputType) {
        $inputTypes = $this->getAttributeInputTypes();
        if (!empty($inputTypes[$inputType]['source_model'])) {
            return $inputTypes[$inputType]['source_model'];
        }
        return null;
    }

    /**
     * get attribute input types
     *
     * @access public
     * @param string $inputType
     * @return array()
     * @author TPL
     */
    public function getAttributeInputTypes($inputType = null) {
        $inputTypes = array(
            'multiselect' => array(
                'backend_model' => 'eav/entity_attribute_backend_array'
            ),
            'boolean' => array(
                'source_model' => 'eav/entity_attribute_source_boolean'
            ),
            'file' => array(
                'backend_model' => 'tpl_eventsmanager/event_attribute_backend_file'
            ),
            'image' => array(
                'backend_model' => 'tpl_eventsmanager/event_attribute_backend_image'
            ),
        );

        if (is_null($inputType)) {
            return $inputTypes;
        } else if (isset($inputTypes[$inputType])) {
            return $inputTypes[$inputType];
        }
        return array();
    }

    /**
     * get event attribute backend model
     *
     * @access public
     * @param string $inputType
     * @return mixed (string|null)
     * @author TPL
     */
    public function getAttributeBackendModelByInputType($inputType) {
        $inputTypes = $this->getAttributeInputTypes();
        if (!empty($inputTypes[$inputType]['backend_model'])) {
            return $inputTypes[$inputType]['backend_model'];
        }
        return null;
    }

    /**
     * filter attribute content
     *
     * @access public
     * @param Tpl_EventsManager_Model_Event $event
     * @param string $attributeHtml
     * @param string @attributeName
     * @return string
     * @author TPL
     */
    public function eventAttribute($event, $attributeHtml, $attributeName) {
        $attribute = Mage::getSingleton('eav/config')->getAttribute(
                Tpl_EventsManager_Model_Event::ENTITY, $attributeName
        );
        if ($attribute && $attribute->getId() && !$attribute->getIsWysiwygEnabled()) {
            if ($attribute->getFrontendInput() == 'textarea') {
                $attributeHtml = nl2br($attributeHtml);
            }
        }
        if ($attribute->getIsWysiwygEnabled()) {
            $attributeHtml = $this->_getTemplateProcessor()->filter($attributeHtml);
        }
        return $attributeHtml;
    }

    /**
     * get the template processor
     *
     * @access protected
     * @return Mage_Catalog_Model_Template_Filter
     * @author TPL
     */
    protected function _getTemplateProcessor() {
        if (null === $this->_templateProcessor) {
            $this->_templateProcessor = Mage::helper('catalog')->getPageTemplateProcessor();
        }
        return $this->_templateProcessor;
    }

    /*
     * Send mail according to invitation response of customer 
     * by pooja gujarathi 
     */

    public function sendInvitationMail($status) {

        $current_user_email = Mage::getSingleton('customer/session')->getCustomer()->getEmail(); // get current user's email id
        $current_user_name = Mage::getSingleton('customer/session')->getCustomer()->getName(); // get current user's Name
        //START: Send mail to Sellers
        $emailTemplate = Mage::getModel('core/email_template')->loadDefault('customer_invitation_mail');
        //Create an array of variables to assign to template
        $emailTemplateVariables = array();
        $emailTemplateVariables['name'] = $current_user_name;
        $emailTemplateVariables['email'] = $current_user_email;
        $emailTemplateVariables['status'] = $status;
        $senderEmail = Mage::getStoreConfig('trans_email/ident_support/email');
        //$senderName = Mage::getStoreConfig('trans_email/ident_support/name');
        $processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables);
        $mail = Mage::getModel('core/email')
                ->setToName($current_user_name)
                ->setToEmail($current_user_email)
                ->setBody($processedTemplate)
                ->setSubject('Subject : Product Invitation')
                ->setFromEmail($senderEmail)
                ->setFromName('TPL Product Event')
                ->setType('html');
        return $mail;
        //END: Send mail to sellers
    }
    
    /*
     * Send mail according to cancle event change option
     * by pooja gujarathi 
     */

    public function sendCancelMail($email,$name,$status,$event_name,$event_link) {

//        $current_user_email = Mage::getSingleton('customer/session')->getCustomer()->getEmail(); // get current user's email id
//        $current_user_name = Mage::getSingleton('customer/session')->getCustomer()->getName(); // get current user's Name
//        //START: Send mail to Sellers
        $emailTemplate = Mage::getModel('core/email_template')->loadDefault('event_cancel_mail');
        //Create an array of variables to assign to template
        $emailTemplateVariables = array();
        $emailTemplateVariables['name'] = $name;
        $emailTemplateVariables['email'] = $email;
        $emailTemplateVariables['status'] = $status;
        $emailTemplateVariables['event_name'] = $event_name;
        $emailTemplateVariables['event_link'] = $event_link;
        
        $senderEmail = Mage::getStoreConfig('trans_email/ident_support/email');
        //$senderName = Mage::getStoreConfig('trans_email/ident_support/name');
        $processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables);
        $mail = Mage::getModel('core/email')
                ->setToName($name)
                ->setToEmail($email)
                ->setBody($processedTemplate)
                ->setSubject('Subject :Event Status Update')
                ->setFromEmail($senderEmail)
                ->setFromName('Events Manager')
                ->setType('html');
        return $mail;
        //END: Send mail to sellers
    }


    // for validation on view .. added by shubham
    public function validateCurrentEvent() {
        $event_model = Mage::getModel('tpl_eventsmanager/event');
        $event = Mage::registry('current_event');

        $event_levelWise = $event_model->getResource()->getAttribute("event_level");
        $global_event = $event_levelWise->getSource()->getOptionId('Global Event');
        $global_event_loggedIn = $event_levelWise->getSource()->getOptionId('Global Event ( Only visible Logged In Customers )');
        $groupLevel_event = $event_levelWise->getSource()->getOptionId('Group Level Event');
        $userLevel_event = $event_levelWise->getSource()->getOptionId('User Level Event');
        $event_level = $event->getEventLevel();
        switch ($event_level) {
            case $global_event:
                break;
            case $global_event_loggedIn:
                if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
                    return false;
                }
                break;
            case $groupLevel_event:
                if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
                    // if customer is not logged in then return
                    return false;
                } else {
                    $customer_groupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
                    if (($customer_groupId != $event->getGroupId())) {
                        // if currrent customers group is not equals to current events group then return
                        return false;
                    }
                }
                break;
            case $userLevel_event:
                if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
                    // if customer is not logged in then return
                    return false;
                } else {
                    if (($event->getUserEmail() != Mage::getSingleton('customer/session')->getCustomer()->getEmail())) {
                        // if currrent customers email is not equals to current events user email then return
                        return false;
                    }
                }
                break;
        }

        return $event;
    }

}
