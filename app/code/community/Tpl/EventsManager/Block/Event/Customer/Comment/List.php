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
 * Event customer comments list
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Block_Event_Customer_Comment_List extends Mage_Customer_Block_Account_Dashboard
{
    /**
     * Event comments collection
     *
     * @var Tpl_EventsManager_Model_Resource_Event_Comment_Event_Collection
     */
    protected $_collection;

    /**
     * Initializes collection
     *
     * @access public
     * @author TPL
     */
    protected function _construct()
    {
        $this->_collection = Mage::getResourceModel(
            'tpl_eventsmanager/event_comment_event_collection'
        );
        $this->_collection
            ->setStoreFilter(Mage::app()->getStore()->getId(), true)
            ->addAttributeToFilter('status', 1) //only active

            ->addStatusFilter(Tpl_EventsManager_Model_Event_Comment::STATUS_APPROVED) //only approved comments
            ->addCustomerFilter(Mage::getSingleton('customer/session')->getCustomerId()) //only my comments
            ->setDateOrder();
    }

    /**
     * Gets collection items count
     *
     * @access public
     * @return int
     * @author TPL
     */
    public function count()
    {
        return $this->_collection->getSize();
    }

    /**
     * Get html code for toolbar
     *
     * @access public
     * @return string
     * @author TPL
     */
    public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }

    /**
     * Initializes toolbar
     *
     * @access protected
     * @return Mage_Core_Block_Abstract
     * @author TPL
     */
    protected function _prepareLayout()
    {
        $toolbar = $this->getLayout()->createBlock('page/html_pager', 'customer_event_comments.toolbar')
            ->setCollection($this->getCollection());

        $this->setChild('toolbar', $toolbar);
        return parent::_prepareLayout();
    }

    /**
     * Get collection
     *
     * @access protected
     * @return Tpl_EventsManager_Model_Resource_Event_Comment_Event_Collection
     * @author TPL
     */
    protected function _getCollection()
    {
        return $this->_collection;
    }

    /**
     * Get collection
     *
     * @access public
     * @return Tpl_EventsManager_Model_Resource_Event_Comment_Event_Collection
     * @author TPL
     */
    public function getCollection()
    {
        return $this->_getCollection();
    }

    /**
     * Get review link
     *
     * @access public
     * @param mixed $comment
     * @return string
     * @author TPL
     */
    public function getCommentLink($comment)
    {
        if ($comment instanceof Varien_Object) {
            $comment = $comment->getCtCommentId();
        }
        return Mage::getUrl(
            'tpl_eventsmanager/event_customer_comment/view/',
            array('id' => $comment)
        );
    }

    /**
     * Get product link
     *
     * @access public
     * @param mixed $comment
     * @return string
     * @author TPL
     */
    public function getEventLink($comment)
    {
        return $comment->getEventUrl();
    }

    /**
     * Format date in short format
     *
     * @access public
     * @param $date
     * @return string
     * @author TPL
     */
    public function dateFormat($date)
    {
        return $this->formatDate($date, Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
    }
}
