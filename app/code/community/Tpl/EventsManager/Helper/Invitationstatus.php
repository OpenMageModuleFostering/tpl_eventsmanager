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
 * Invitation Status helper
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Helper_Invitationstatus extends Mage_Core_Helper_Abstract
{

    /**
     * get the url to the invitations status list page
     *
     * @access public
     * @return string
     * @author TPL
     */
    public function getInvitationsstatusUrl()
    {
        if ($listKey = Mage::getStoreConfig('tpl_eventsmanager/invitationstatus/url_rewrite_list')) {
            return Mage::getUrl('', array('_direct'=>$listKey));
        }
        return Mage::getUrl('tpl_eventsmanager/invitationstatus/index');
    }

    /**
     * check if breadcrumbs can be used
     *
     * @access public
     * @return bool
     * @author TPL
     */
    public function getUseBreadcrumbs()
    {
        return Mage::getStoreConfigFlag('tpl_eventsmanager/invitationstatus/breadcrumbs');
    }

    /**
     * check if the rss for invitation status is enabled
     *
     * @access public
     * @return bool
     * @author TPL
     */
    public function isRssEnabled()
    {
        return  Mage::getStoreConfigFlag('rss/config/active') &&
            Mage::getStoreConfigFlag('tpl_eventsmanager/invitationstatus/rss');
    }

    /**
     * get the link to the invitation status rss list
     *
     * @access public
     * @return string
     * @author TPL
     */
    public function getRssUrl()
    {
        return Mage::getUrl('tpl_eventsmanager/invitationstatus/rss');
    }

    /**
     * get base files dir
     *
     * @access public
     * @return string
     * @author TPL
     */
    public function getFileBaseDir()
    {
        return Mage::getBaseDir('media').DS.'invitationstatus'.DS.'file';
    }

    /**
     * get base file url
     *
     * @access public
     * @return string
     * @author TPL
     */
    public function getFileBaseUrl()
    {
        return Mage::getBaseUrl('media').'invitationstatus'.'/'.'file';
    }

    /**
     * get invitationstatus attribute source model
     *
     * @access public
     * @param string $inputType
     * @return mixed (string|null)
     * @author TPL
     */
     public function getAttributeSourceModelByInputType($inputType)
     {
         $inputTypes = $this->getAttributeInputTypes();
         if (!empty($inputTypes[$inputType]['source_model'])) {
             return $inputTypes[$inputType]['source_model'];
         }
         return null;
     }

    /**
     * get attribute input types
     *
     * @access public
     * @param string $inputType
     * @return array()
     * @author TPL
     */
    public function getAttributeInputTypes($inputType = null)
    {
        $inputTypes = array(
            'multiselect' => array(
                'backend_model' => 'eav/entity_attribute_backend_array'
            ),
            'boolean'     => array(
                'source_model'  => 'eav/entity_attribute_source_boolean'
            ),
            'file'          => array(
                'backend_model' => 'tpl_eventsmanager/invitationstatus_attribute_backend_file'
            ),
            'image'          => array(
                'backend_model' => 'tpl_eventsmanager/invitationstatus_attribute_backend_image'
            ),
        );

        if (is_null($inputType)) {
            return $inputTypes;
        } else if (isset($inputTypes[$inputType])) {
            return $inputTypes[$inputType];
        }
        return array();
    }

    /**
     * get invitationstatus attribute backend model
     *
     * @access public
     * @param string $inputType
     * @return mixed (string|null)
     * @author TPL
     */
    public function getAttributeBackendModelByInputType($inputType)
    {
        $inputTypes = $this->getAttributeInputTypes();
        if (!empty($inputTypes[$inputType]['backend_model'])) {
            return $inputTypes[$inputType]['backend_model'];
        }
        return null;
    }

    /**
     * filter attribute content
     *
     * @access public
     * @param Tpl_EventsManager_Model_Invitationstatus $invitationstatus
     * @param string $attributeHtml
     * @param string @attributeName
     * @return string
     * @author TPL
     */
    public function invitationstatusAttribute($invitationstatus, $attributeHtml, $attributeName)
    {
        $attribute = Mage::getSingleton('eav/config')->getAttribute(
            Tpl_EventsManager_Model_Invitationstatus::ENTITY,
            $attributeName
        );
        if ($attribute && $attribute->getId() && !$attribute->getIsWysiwygEnabled()) {
            if ($attribute->getFrontendInput() == 'textarea') {
                $attributeHtml = nl2br($attributeHtml);
            }
        }
        if ($attribute->getIsWysiwygEnabled()) {
            $attributeHtml = $this->_getTemplateProcessor()->filter($attributeHtml);
        }
        return $attributeHtml;
    }

    /**
     * get the template processor
     *
     * @access protected
     * @return Mage_Catalog_Model_Template_Filter
     * @author TPL
     */
    protected function _getTemplateProcessor()
    {
        if (null === $this->_templateProcessor) {
            $this->_templateProcessor = Mage::helper('catalog')->getPageTemplateProcessor();
        }
        return $this->_templateProcessor;
    }
}
