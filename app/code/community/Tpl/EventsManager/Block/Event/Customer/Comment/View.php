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
class Tpl_EventsManager_Block_Event_Customer_Comment_View extends Mage_Customer_Block_Account_Dashboard
{
    /**
     * get current comment
     *
     * @access public
     * @return Tpl_EventsManager_Model_Event_Comment
     * @author TPL
     */
    public function getComment()
    {
        return Mage::registry('current_comment');
    }

    /**
     * get current event
     *
     * @access public
     * @return Tpl_EventsManager_Model_Event
     * @author TPL
     */
    public function getEvent()
    {
        return Mage::registry('current_event');
    }
}
