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
 * Invitation Status view block
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Block_Invitationstatus_View extends Mage_Core_Block_Template
{
    /**
     * get the current invitation status
     *
     * @access public
     * @return mixed (Tpl_EventsManager_Model_Invitationstatus|null)
     * @author TPL
     */
    public function getCurrentInvitationstatus()
    {
        return Mage::registry('current_invitationstatus');
    }
}
