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
 * Admin search model
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Model_Adminhtml_Search_Invitationstatus extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return Tpl_EventsManager_Model_Adminhtml_Search_Invitationstatus
     * @author TPL
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('tpl_eventsmanager/invitationstatus_collection')
            ->addAttributeToFilter('event_name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $invitationstatus) {
            $arr[] = array(
                'id'          => 'invitationstatus/1/'.$invitationstatus->getId(),
                'type'        => Mage::helper('tpl_eventsmanager')->__('Invitation Status'),
                'name'        => $invitationstatus->getEventName(),
                'description' => $invitationstatus->getEventName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/eventsmanager_invitationstatus/edit',
                    array('id'=>$invitationstatus->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
