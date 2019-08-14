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
 * Invitation Status comment edit form tab
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Block_Adminhtml_Invitationstatus_Comment_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return EventsManager_Invitationstatus_Block_Adminhtml_Invitationstatus_Comment_Edit_Tab_Form
     * @author TPL
     */
    protected function _prepareForm()
    {
        $invitationstatus = Mage::registry('current_invitationstatus');
        $comment    = Mage::registry('current_comment');
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('comment_');
        $form->setFieldNameSuffix('comment');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'comment_form',
            array('legend'=>Mage::helper('tpl_eventsmanager')->__('Comment'))
        );
        $fieldset->addField(
            'invitationstatus_id',
            'hidden',
            array(
                'name'  => 'invitationstatus_id',
                'after_element_html' => '<a href="'.
                    Mage::helper('adminhtml')->getUrl(
                        'adminhtml/eventsmanager_invitationstatus/edit',
                        array(
                            'id'=>$invitationstatus->getId()
                        )
                    ).
                    '" target="_blank">'.
                    Mage::helper('tpl_eventsmanager')->__('Invitation Status').
                    ' : '.$invitationstatus->getEventName().'</a>'
            )
        );
        $fieldset->addField(
            'title',
            'text',
            array(
                'label'    => Mage::helper('tpl_eventsmanager')->__('Title'),
                'name'     => 'title',
                'required' => true,
                'class'    => 'required-entry',
            )
        );
        $fieldset->addField(
            'comment',
            'textarea',
            array(
                'label'    => Mage::helper('tpl_eventsmanager')->__('Comment'),
                'name'     => 'comment',
                'required' => true,
                'class'    => 'required-entry',
            )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'    => Mage::helper('tpl_eventsmanager')->__('Status'),
                'name'     => 'status',
                'required' => true,
                'class'    => 'required-entry',
                'values'   => array(
                    array(
                        'value' => Tpl_EventsManager_Model_Invitationstatus_Comment::STATUS_PENDING,
                        'label' => Mage::helper('tpl_eventsmanager')->__('Pending'),
                    ),
                    array(
                        'value' => Tpl_EventsManager_Model_Invitationstatus_Comment::STATUS_APPROVED,
                        'label' => Mage::helper('tpl_eventsmanager')->__('Approved'),
                    ),
                    array(
                        'value' => Tpl_EventsManager_Model_Invitationstatus_Comment::STATUS_REJECTED,
                        'label' => Mage::helper('tpl_eventsmanager')->__('Rejected'),
                    ),
                ),
            )
        );
        $configuration = array(
             'label' => Mage::helper('tpl_eventsmanager')->__('Poster name'),
             'name'  => 'name',
             'required'  => true,
             'class' => 'required-entry',
        );
        if ($comment->getCustomerId()) {
            $configuration['after_element_html'] = '<a href="'.
                Mage::helper('adminhtml')->getUrl(
                    'adminhtml/customer/edit',
                    array(
                        'id'=>$comment->getCustomerId()
                    )
                ).
                '" target="_blank">'.
                Mage::helper('tpl_eventsmanager')->__('Customer profile').'</a>';
        }
        $fieldset->addField('name', 'text', $configuration);
        $fieldset->addField(
            'email',
            'text',
            array(
                'label' => Mage::helper('tpl_eventsmanager')->__('Poster e-mail'),
                'name'  => 'email',
                'required'  => true,
                'class' => 'required-entry',
            )
        );
        $fieldset->addField(
            'customer_id',
            'hidden',
            array(
                'name'  => 'customer_id',
            )
        );
        if (Mage::app()->isSingleStoreMode()) {
            $fieldset->addField(
                'store_id',
                'hidden',
                array(
                    'name'      => 'stores[]',
                    'value'     => Mage::app()->getStore(true)->getId()
                )
            );
            Mage::registry('current_comment')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $form->addValues($this->getComment()->getData());
        return parent::_prepareForm();
    }

    /**
     * get the current comment
     *
     * @access public
     * @return Tpl_EventsManager_Model_Invitationstatus_Comment
     */
    public function getComment()
    {
        return Mage::registry('current_comment');
    }
}
