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
 * Invitation Status model
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Model_Invitationstatus extends Mage_Catalog_Model_Abstract {

    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY = 'tpl_eventsmanager_invitationstatus';
    const CACHE_TAG = 'tpl_eventsmanager_invitationstatus';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'tpl_eventsmanager_invitationstatus';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'invitationstatus';

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function _construct() {
        parent::_construct();
        $this->_init('tpl_eventsmanager/invitationstatus');
    }

    /**
     * before save invitation status
     *
     * @access protected
     * @return Tpl_EventsManager_Model_Invitationstatus
     * @author TPL
     */
    protected function _beforeSave() {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * get the url to the invitation status details page
     *
     * @access public
     * @return string
     * @author TPL
     */
    public function getInvitationstatusUrl() {
        if ($this->getUrlKey()) {
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('tpl_eventsmanager/invitationstatus/url_prefix')) {
                $urlKey .= $prefix . '/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('tpl_eventsmanager/invitationstatus/url_suffix')) {
                $urlKey .= '.' . $suffix;
            }
            return Mage::getUrl('', array('_direct' => $urlKey));
        }
        return Mage::getUrl('tpl_eventsmanager/invitationstatus/view', array('id' => $this->getId()));
    }

    /**
     * check URL key
     *
     * @access public
     * @param string $urlKey
     * @param bool $active
     * @return mixed
     * @author TPL
     */
    public function checkUrlKey($urlKey, $active = true) {
        return $this->_getResource()->checkUrlKey($urlKey, $active);
    }

    /**
     * save invitation status relation
     *
     * @access public
     * @return Tpl_EventsManager_Model_Invitationstatus
     * @author TPL
     */
    protected function _afterSave() {
        if(Mage::getStoreConfig('tpl_eventsmanager/customsettings/event_invite_response_emails', Mage::app()->getStore())) {
            $invitationstatus_model = Mage::getModel('tpl_eventsmanager/invitationstatus')->getCollection();
            $event_invitation_status = $invitationstatus_model->getResource()->getAttribute("event_invitation_status");

            $yes = $event_invitation_status->getSource()->getOptionId('Accepted');
            $no = $event_invitation_status->getSource()->getOptionId('Rejected');
            // Send Invitation mail to customer 
            $mail = "";
            if ($this->getEventInvitationStatus() == $yes) {
                $mail = Mage::helper('tpl_eventsmanager/event')->sendInvitationMail("Accepted");
            } else {
                $mail = Mage::helper('tpl_eventsmanager/event')->sendInvitationMail("Rejected");
            }

            try {
                $mail->send();
            } catch (Exception $e) {
                
            }
        }


        return parent::_afterSave();
    }

    /**
     * Retrieve default attribute set id
     *
     * @access public
     * @return int
     * @author TPL
     */
    public function getDefaultAttributeSetId() {
        return $this->getResource()->getEntityType()->getDefaultAttributeSetId();
    }

    /**
     * get attribute text value
     *
     * @access public
     * @param $attributeCode
     * @return string
     * @author TPL
     */
    public function getAttributeText($attributeCode) {
        $text = $this->getResource()
                ->getAttribute($attributeCode)
                ->getSource()
                ->getOptionText($this->getData($attributeCode));
        if (is_array($text)) {
            return implode(', ', $text);
        }
        return $text;
    }

    /**
     * check if comments are allowed
     *
     * @access public
     * @return array
     * @author TPL
     */
    public function getAllowComments() {
        if ($this->getData('allow_comment') == Tpl_EventsManager_Model_Adminhtml_Source_Yesnodefault::NO) {
            return false;
        }
        if ($this->getData('allow_comment') == Tpl_EventsManager_Model_Adminhtml_Source_Yesnodefault::YES) {
            return true;
        }
        return Mage::getStoreConfigFlag('tpl_eventsmanager/invitationstatus/allow_comment');
    }

    /**
     * get default values
     *
     * @access public
     * @return array
     * @author TPL
     */
    public function getDefaultValues() {
        $values = array();
        $values['status'] = 1;
        $values['in_rss'] = 1;
        $values['allow_comment'] = Tpl_EventsManager_Model_Adminhtml_Source_Yesnodefault::USE_DEFAULT;
        return $values;
    }

}
