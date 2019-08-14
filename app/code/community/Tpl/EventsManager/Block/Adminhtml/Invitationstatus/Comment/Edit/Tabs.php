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
 * Invitation Status comment admin edit tabs
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Block_Adminhtml_Invitationstatus_Comment_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('invitationstatus_comment_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('tpl_eventsmanager')->__('Invitation Status Comment'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return Tpl_EventsManager_Block_Adminhtml_Invitationstatus_Edit_Tabs
     * @author TPL
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_invitationstatus_comment',
            array(
                'label'   => Mage::helper('tpl_eventsmanager')->__('Invitation Status comment'),
                'title'   => Mage::helper('tpl_eventsmanager')->__('Invitation Status comment'),
                'content' => $this->getLayout()->createBlock(
                    'tpl_eventsmanager/adminhtml_invitationstatus_comment_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addTab(
                'form_store_invitationstatus_comment',
                array(
                    'label'   => Mage::helper('tpl_eventsmanager')->__('Store views'),
                    'title'   => Mage::helper('tpl_eventsmanager')->__('Store views'),
                    'content' => $this->getLayout()->createBlock(
                        'tpl_eventsmanager/adminhtml_invitationstatus_comment_edit_tab_stores'
                    )
                    ->toHtml(),
                )
            );
        }
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve comment
     *
     * @access public
     * @return Tpl_EventsManager_Model_Invitationstatus_Comment
     * @author TPL
     */
    public function getComment()
    {
        return Mage::registry('current_comment');
    }
}
