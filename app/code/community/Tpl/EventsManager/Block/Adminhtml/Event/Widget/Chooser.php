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
 * Event admin widget chooser
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */

class Tpl_EventsManager_Block_Adminhtml_Event_Widget_Chooser extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Block construction, prepare grid params
     *
     * @access public
     * @param array $arguments Object data
     * @return void
     * @author TPL
     */
    public function __construct($arguments=array())
    {
        parent::__construct($arguments);
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        $this->setDefaultFilter(array('chooser_status' => '1'));
    }

    /**
     * Prepare chooser element HTML
     *
     * @access public
     * @param Varien_Data_Form_Element_Abstract $element Form Element
     * @return Varien_Data_Form_Element_Abstract
     * @author TPL
     */
    public function prepareElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $uniqId = Mage::helper('core')->uniqHash($element->getId());
        $sourceUrl = $this->getUrl(
            '*/eventsmanager_event_widget/chooser',
            array('uniq_id' => $uniqId)
        );
        $chooser = $this->getLayout()->createBlock('widget/adminhtml_widget_chooser')
            ->setElement($element)
            ->setTranslationHelper($this->getTranslationHelper())
            ->setConfig($this->getConfig())
            ->setFieldsetId($this->getFieldsetId())
            ->setSourceUrl($sourceUrl)
            ->setUniqId($uniqId);
        if ($element->getValue()) {
            $event = Mage::getModel('tpl_eventsmanager/event')->load($element->getValue());
            if ($event->getId()) {
                $chooser->setLabel($event->getEventName());
            }
        }
        $element->setData('after_element_html', $chooser->toHtml());
        return $element;
    }

    /**
     * Grid Row JS Callback
     *
     * @access public
     * @return string
     * @author TPL
     */
    public function getRowClickCallback()
    {
        $chooserJsObject = $this->getId();
        $js = '
            function (grid, event) {
                var trElement = Event.findElement(event, "tr");
                var eventId = trElement.down("td").innerHTML.replace(/^\s+|\s+$/g,"");
                var eventTitle = trElement.down("td").next().innerHTML;
                '.$chooserJsObject.'.setElementValue(eventId);
                '.$chooserJsObject.'.setElementLabel(eventTitle);
                '.$chooserJsObject.'.close();
            }
        ';
        return $js;
    }

    /**
     * Prepare a static blocks collection
     *
     * @access protected
     * @return Tpl_EventsManager_Block_Adminhtml_Event_Widget_Chooser
     * @author TPL
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('tpl_eventsmanager/event')->getCollection();
        $collection->addAttributeToSelect('event_name');
        $collection->addAttributeToSelect('status');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare columns for the a grid
     *
     * @access protected
     * @return Tpl_EventsManager_Block_Adminhtml_Event_Widget_Chooser
     * @author TPL
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'chooser_id',
            array(
                'header' => Mage::helper('tpl_eventsmanager')->__('Id'),
                'align'  => 'right',
                'index'  => 'entity_id',
                'type'   => 'number',
                'width'  => 50
            )
        );

        $this->addColumn(
            'chooser_event_name',
            array(
                'header' => Mage::helper('tpl_eventsmanager')->__('Event Name'),
                'align'  => 'left',
                'index'  => 'event_name',
            )
        );
        $this->addColumn(
            'chooser_status',
            array(
                'header'  => Mage::helper('tpl_eventsmanager')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    0 => Mage::helper('tpl_eventsmanager')->__('Disabled'),
                    1 => Mage::helper('tpl_eventsmanager')->__('Enabled')
                ),
            )
        );
        return parent::_prepareColumns();
    }

    /**
     * get url for grid
     *
     * @access public
     * @return string
     * @author TPL
     */
    public function getGridUrl()
    {
        return $this->getUrl(
            'adminhtml/eventsmanager_event_widget/chooser',
            array('_current' => true)
        );
    }
}
