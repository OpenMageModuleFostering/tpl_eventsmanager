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
 * Invitation Status image helper
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Helper_Invitationstatus_Image extends Tpl_EventsManager_Helper_Image_Abstract
{
    /**
     * image placeholder
     * @var string
     */
    protected $_placeholder = 'images/placeholder/invitationstatus.jpg';
    /**
     * image subdir
     * @var string
     */
    protected $_subdir      = 'invitationstatus';
}
