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
 * Invitation Status comment admin edit form
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Block_Adminhtml_Invitationstatus_Comment_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'tpl_eventsmanager';
        $this->_controller = 'adminhtml_invitationstatus_comment';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('tpl_eventsmanager')->__('Save Invitation Status comment')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('tpl_eventsmanager')->__('Delete Invitation Status comment')
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'        => Mage::helper('tpl_eventsmanager')->__('Save And Continue Edit'),
                'onclick'    => 'saveAndContinueEdit()',
                'class'        => 'save',
            ),
            -100
        );
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * get the edit form header
     *
     * @access public
     * @return string
     * @author TPL
     */
    public function getHeaderText()
    {
        if (Mage::registry('comment_data') && Mage::registry('comment_data')->getId()) {
            return Mage::helper('tpl_eventsmanager')->__(
                "Edit Invitation Status comment '%s'",
                $this->escapeHtml(Mage::registry('comment_data')->getTitle())
            );
        }
        return '';
    }
}
