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
 * Invitation Status admin edit tabs
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Block_Adminhtml_Invitationstatus_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author TPL
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('invitationstatus_info_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('tpl_eventsmanager')->__('Invitation Status Information'));
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Tpl_EventsManager_Block_Adminhtml_Invitationstatus_Edit_Tabs
     * @author TPL
     */
    protected function _prepareLayout()
    {
        $invitationstatus = $this->getInvitationstatus();
        $entity = Mage::getModel('eav/entity_type')
            ->load('tpl_eventsmanager_invitationstatus', 'entity_type_code');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
                ->setEntityTypeFilter($entity->getEntityTypeId());
        $attributes->addFieldToFilter(
            'attribute_code',
            array(
                'nin' => array('meta_title', 'meta_description', 'meta_keywords')
            )
        );
        $attributes->getSelect()->order('additional_table.position', 'ASC');

        $this->addTab(
            'info',
            array(
                'label'   => Mage::helper('tpl_eventsmanager')->__('Invitation Status Information'),
                'content' => $this->getLayout()->createBlock(
                    'tpl_eventsmanager/adminhtml_invitationstatus_edit_tab_attributes'
                )
                ->setAttributes($attributes)
                ->toHtml(),
            )
        );
        $seoAttributes = Mage::getResourceModel('eav/entity_attribute_collection')
            ->setEntityTypeFilter($entity->getEntityTypeId())
            ->addFieldToFilter(
                'attribute_code',
                array(
                    'in' => array('meta_title', 'meta_description', 'meta_keywords')
                )
            );
        $seoAttributes->getSelect()->order('additional_table.position', 'ASC');

        $this->addTab(
            'meta',
            array(
                'label'   => Mage::helper('tpl_eventsmanager')->__('Meta'),
                'title'   => Mage::helper('tpl_eventsmanager')->__('Meta'),
                'content' => $this->getLayout()->createBlock(
                    'tpl_eventsmanager/adminhtml_invitationstatus_edit_tab_attributes'
                )
                ->setAttributes($seoAttributes)
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve invitation status entity
     *
     * @access public
     * @return Tpl_EventsManager_Model_Invitationstatus
     * @author TPL
     */
    public function getInvitationstatus()
    {
        return Mage::registry('current_invitationstatus');
    }
}
