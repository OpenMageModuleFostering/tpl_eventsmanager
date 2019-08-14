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
 * Invitation Status RSS block
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Block_Invitationstatus_Rss extends Mage_Rss_Block_Abstract
{
    /**
     * Cache tag constant for feed reviews
     *
     * @var string
     */
    const CACHE_TAG = 'block_html_eventsmanager_invitationstatus_rss';

    /**
     * constructor
     *
     * @access protected
     * @return void
     * @author TPL
     */
    protected function _construct()
    {
        $this->setCacheTags(array(self::CACHE_TAG));
        /*
         * setting cache to save the rss for 10 minutes
         */
        $this->setCacheKey('tpl_eventsmanager_invitationstatus_rss');
        $this->setCacheLifetime(600);
    }

    /**
     * toHtml method
     *
     * @access protected
     * @return string
     * @author TPL
     */
    protected function _toHtml()
    {
        $url    = Mage::helper('tpl_eventsmanager/invitationstatus')->getInvitationsstatusUrl();
        $title  = Mage::helper('tpl_eventsmanager')->__('Invitations Status');
        $rssObj = Mage::getModel('rss/rss');
        $data  = array(
            'title'       => $title,
            'description' => $title,
            'link'        => $url,
            'charset'     => 'UTF-8',
        );
        $rssObj->_addHeader($data);
        $collection = Mage::getModel('tpl_eventsmanager/invitationstatus')->getCollection()
            ->addFieldToFilter('status', 1)
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('in_rss', 1)
            ->setOrder('created_at');
        $collection->load();
        foreach ($collection as $item) {
            $description = '<p>';
            $description .= '<div>'.
                Mage::helper('tpl_eventsmanager')->__('Event Id').': 
                '.$item->getEventId().
                '</div>';
            $description .= '<div>'.
                Mage::helper('tpl_eventsmanager')->__('Event Name').': 
                '.$item->getEventName().
                '</div>';
            $description .= '<div>'.
                Mage::helper('tpl_eventsmanager')->__('Customer Name').': 
                '.$item->getCustomerName().
                '</div>';
            $description .= '<div>'.
                Mage::helper('tpl_eventsmanager')->__('Customer Email').': 
                '.$item->getCustomerEmail().
                '</div>';
            $description .= '<div>'.
                Mage::helper('tpl_eventsmanager')->__('Customer Address').': 
                '.$item->getCustomerAddress().
                '</div>';
            $description .= '<div>'.
                Mage::helper('tpl_eventsmanager')->__('Customer Contact Number').': 
                '.$item->getCustomerContactNumber().
                '</div>';
            $description .= '<div>'.
                Mage::helper('tpl_eventsmanager')->__("Invitation Status").': '
                .$item->getAttributeText('event_invitation_status').
                '</div>';
            $description .= '</p>';
            $data = array(
                'title'       => $item->getEventName(),
                'link'        => $item->getInvitationstatusUrl(),
                'description' => $description
            );
            $rssObj->_addEntry($data);
        }
        return $rssObj->createRssXml();
    }
}
