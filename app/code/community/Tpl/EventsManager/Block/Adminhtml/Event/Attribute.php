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
 * Event admin attribute block
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Block_Adminhtml_Event_Attribute extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @author TPL
     */
    public function __construct()
    {
        $this->_controller = 'adminhtml_event_attribute';
        $this->_blockGroup = 'tpl_eventsmanager';
        $this->_headerText = Mage::helper('tpl_eventsmanager')->__('Manage Event Attributes');
        parent::__construct();
        $this->_updateButton(
            'add',
            'label',
            Mage::helper('tpl_eventsmanager')->__('Add New Event Attribute')
        );
    }
}
