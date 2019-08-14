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
 * Adminhtml invitation status attribute edit page tabs
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Block_Adminhtml_Invitationstatus_Attribute_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * constructor
     *
     * @access public
     * @author TPL
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('invitationstatus_attribute_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('tpl_eventsmanager')->__('Attribute Information'));
    }

    /**
     * add attribute tabs
     *
     * @access protected
     * @return Tpl_EventsManager_Adminhtml_Invitationstatus_Attribute_Edit_Tabs
     * @author TPL
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'main',
            array(
                'label'     => Mage::helper('tpl_eventsmanager')->__('Properties'),
                'title'     => Mage::helper('tpl_eventsmanager')->__('Properties'),
                'content'   => $this->getLayout()->createBlock(
                    'tpl_eventsmanager/adminhtml_invitationstatus_attribute_edit_tab_main'
                )
                ->toHtml(),
                'active'    => true
            )
        );
        $this->addTab(
            'labels',
            array(
                'label'     => Mage::helper('tpl_eventsmanager')->__('Manage Label / Options'),
                'title'     => Mage::helper('tpl_eventsmanager')->__('Manage Label / Options'),
                'content'   => $this->getLayout()->createBlock(
                    'tpl_eventsmanager/adminhtml_invitationstatus_attribute_edit_tab_options'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }
}
