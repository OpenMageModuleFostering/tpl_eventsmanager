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
 * Event RSS block
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Block_Event_Rss extends Mage_Rss_Block_Abstract
{
    /**
     * Cache tag constant for feed reviews
     *
     * @var string
     */
    const CACHE_TAG = 'block_html_eventsmanager_event_rss';

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
        $this->setCacheKey('tpl_eventsmanager_event_rss');
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
        $url    = Mage::helper('tpl_eventsmanager/event')->getEventsUrl();
        $title  = Mage::helper('tpl_eventsmanager')->__('Events');
        $rssObj = Mage::getModel('rss/rss');
        $data  = array(
            'title'       => $title,
            'description' => $title,
            'link'        => $url,
            'charset'     => 'UTF-8',
        );
        $rssObj->_addHeader($data);
        $collection = Mage::getModel('tpl_eventsmanager/event')->getCollection()
            ->addFieldToFilter('status', 1)
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('in_rss', 1)
            ->setOrder('created_at');
        $collection->load();
        foreach ($collection as $item) {
            $description = '<p>';
            $description .= '<div>'.
                Mage::helper('tpl_eventsmanager')->__('Event Name').': 
                '.$item->getEventName().
                '</div>';
            $description .= '<div>'.Mage::helper('tpl_eventsmanager')->__('From Date').': '.Mage::helper('core')->formatDate($item->getFromDate(), 'full').'</div>';
            $description .= '<div>'.
                Mage::helper('tpl_eventsmanager')->__('From Time').': 
                '.$item->getFromTime().
                '</div>';
            $description .= '<div>'.Mage::helper('tpl_eventsmanager')->__('End Date').': '.Mage::helper('core')->formatDate($item->getEndDate(), 'full').'</div>';
            $description .= '<div>'.
                Mage::helper('tpl_eventsmanager')->__('End Time').': 
                '.$item->getEndTime().
                '</div>';
            $description .= '<div>'.
                Mage::helper('tpl_eventsmanager')->__('Description').': 
                '.$item->getDescription().
                '</div>';
            if ($item->getThumbnail()) {
                $description .= '<div>';
                $description .= Mage::helper('tpl_eventsmanager')->__('Thumbnail');
                $description .= '<img src="'.Mage::helper('tpl_eventsmanager/event_image')->init($item, 'thumbnail')->resize(75).'" alt="'.$this->escapeHtml($item->getEventName()).'" />';
                $description .= '</div>';
            }
            if ($item->getBanner()) {
                $description .= '<div>';
                $description .= Mage::helper('tpl_eventsmanager')->__('Banner Image');
                $description .= '<img src="'.Mage::helper('tpl_eventsmanager/event_image')->init($item, 'banner')->resize(75).'" alt="'.$this->escapeHtml($item->getEventName()).'" />';
                $description .= '</div>';
            }
            $description .= '<div>'.
                Mage::helper('tpl_eventsmanager')->__('Color').': 
                '.$item->getColor().
                '</div>';
            $description .= '<div>'.
                Mage::helper('tpl_eventsmanager')->__("Event Level").': '
                .$item->getAttributeText('event_level').
                '</div>';
            $description .= '<div>'.
                Mage::helper('tpl_eventsmanager')->__("Event Type").': '
                .$item->getAttributeText('event_type').
                '</div>';
            $description .= '<div>'.
                Mage::helper('tpl_eventsmanager')->__('Select Group').': 
                '.$item->getGroupId().
                '</div>';
            $description .= '<div>'.
                Mage::helper('tpl_eventsmanager')->__('User Email').': 
                '.$item->getUserEmail().
                '</div>';
            $description .= '<div>'.Mage::helper('tpl_eventsmanager')->__("Allow Invitation").':'.(($item->getIsInvitable() == 1) ? Mage::helper('tpl_eventsmanager')->__('Yes') : Mage::helper('tpl_eventsmanager')->__('No')).'</div>';
            $description .= '<div>'.Mage::helper('tpl_eventsmanager')->__('Product Launch Date').': '.Mage::helper('core')->formatDate($item->getProductLaunchDate(), 'full').'</div>';
            $description .= '<div>'.
                Mage::helper('tpl_eventsmanager')->__('Product Launch Time').': 
                '.$item->getProductLaunchTime().
                '</div>';
            $description .= '<div>'.
                Mage::helper('tpl_eventsmanager')->__('Address ').': 
                '.$item->getAddress().
                '</div>';
            $description .= '<div>'.
                Mage::helper('tpl_eventsmanager')->__('City').': 
                '.$item->getCity().
                '</div>';
            $description .= '<div>'.
                Mage::helper('tpl_eventsmanager')->__('State').': 
                '.$item->getState().
                '</div>';
            $description .= '<div>'.
                Mage::helper('tpl_eventsmanager')->__('Country').': 
                '.$item->getCountry().
                '</div>';
            $description .= '<div>'.
                Mage::helper('tpl_eventsmanager')->__('Contact Number').': 
                '.$item->getContactNumber().
                '</div>';
            $description .= '<div>'.
                Mage::helper('tpl_eventsmanager')->__('Contact Email').': 
                '.$item->getContactEmail().
                '</div>';
            $description .= '<div>'.
                Mage::helper('tpl_eventsmanager')->__('Postal Code / PIN / Area Code').': 
                '.$item->getPinCode().
                '</div>';
            $description .= '<div>'.Mage::helper('tpl_eventsmanager')->__("Cancel Event").':'.(($item->getCancelEvent() == 1) ? Mage::helper('tpl_eventsmanager')->__('Yes') : Mage::helper('tpl_eventsmanager')->__('No')).'</div>';
            $description .= '</p>';
            $data = array(
                'title'       => $item->getEventName(),
                'link'        => $item->getEventUrl(),
                'description' => $description
            );
            $rssObj->_addEntry($data);
        }
        return $rssObj->createRssXml();
    }
}
