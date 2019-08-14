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
 * Invitation Status comment form block
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author TPL
 */
class Tpl_EventsManager_Block_Invitationstatus_Comment_Form extends Mage_Core_Block_Template
{
    /**
     * initialize
     *
     * @access public
     * @author TPL
     */
    public function __construct()
    {
        $customerSession = Mage::getSingleton('customer/session');
        parent::__construct();
        $data =  Mage::getSingleton('customer/session')->getInvitationstatusCommentFormData(true);
        $data = new Varien_Object($data);
        // add logged in customer name as nickname
        if (!$data->getName()) {
            $customer = $customerSession->getCustomer();
            if ($customer && $customer->getId()) {
                $data->setName($customer->getFirstname());
                $data->setEmail($customer->getEmail());
            }
        }
        $this->setAllowWriteCommentFlag(
            $customerSession->isLoggedIn() ||
            Mage::getStoreConfigFlag('tpl_eventsmanager/invitationstatus/allow_guest_comment')
        );
        if (!$this->getAllowWriteCommentFlag()) {
            $this->setLoginLink(
                Mage::getUrl(
                    'customer/account/login/',
                    array(
                        Mage_Customer_Helper_Data::REFERER_QUERY_PARAM_NAME => Mage::helper('core')->urlEncode(
                            Mage::getUrl('*/*/*', array('_current' => true)) .
                            '#comment-form'
                        )
                    )
                )
            );
        }
        $this->setCommentData($data);
    }

    /**
     * get current invitation status
     *
     * @access public
     * @return Tpl_EventsManager_Model_Invitationstatus
     * @author TPL
     */
    public function getInvitationstatus()
    {
        return Mage::registry('current_invitationstatus');
    }

    /**
     * get form action
     *
     * @access public
     * @return string
     * @author TPL
     */
    public function getAction()
    {
        return Mage::getUrl(
            'tpl_eventsmanager/invitationstatus/commentpost',
            array('id' => $this->getInvitationstatus()->getId())
        );
    }
}
