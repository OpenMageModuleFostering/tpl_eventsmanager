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
 * Admin source yes/no/default model
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Model_Adminhtml_Source_Yesnodefault extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    const YES = 1;
    const NO = 0;
    const USE_DEFAULT = 2;

    /**
     * get possible values
     *
     * @access public
     * @return array
     * @author TPL
     */
    public function toOptionArray()
    {
        return array(
            array(
                'label' => Mage::helper('tpl_eventsmanager')->__('Use default config'),
                'value' => self::USE_DEFAULT
            ),
            array(
                'label' => Mage::helper('tpl_eventsmanager')->__('Yes'),
                'value' => self::YES
            ),
            array(
                'label' => Mage::helper('tpl_eventsmanager')->__('No'),
                'value' => self::NO
            )
        );
    }

    /**
     * Get list of all available values
     *
     * @access public
     * @return array
     * @author TPL
     */
    public function getAllOptions()
    {
        return $this->toOptionArray();
    }
}
