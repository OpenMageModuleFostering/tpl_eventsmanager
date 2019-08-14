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
 * Event admin grid block
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Block_Adminhtml_Event_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author TPL
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('eventGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return Tpl_EventsManager_Block_Adminhtml_Event_Grid
     * @author TPL
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('tpl_eventsmanager/event')
            ->getCollection()
            ->addAttributeToSelect('from_date')
            ->addAttributeToSelect('end_date')
            ->addAttributeToSelect('event_level')
            ->addAttributeToSelect('event_type')
            ->addAttributeToSelect('cancel_event')
            ->addAttributeToSelect('status')
            ->addAttributeToSelect('url_key');
        
        $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
        $store = $this->_getStore();
        $collection->joinAttribute(
            'event_name', 
            'tpl_eventsmanager_event/event_name', 
            'entity_id', 
            null, 
            'inner', 
            $adminStore
        );
        if ($store->getId()) {
            $collection->joinAttribute(
                'tpl_eventsmanager_event_event_name', 
                'tpl_eventsmanager_event/event_name', 
                'entity_id', 
                null, 
                'inner', 
                $store->getId()
            );
        }

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return Tpl_EventsManager_Block_Adminhtml_Event_Grid
     * @author TPL
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('tpl_eventsmanager')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'event_name',
            array(
                'header'    => Mage::helper('tpl_eventsmanager')->__('Event Name'),
                'align'     => 'left',
                'index'     => 'event_name',
            )
        );
        
        if ($this->_getStore()->getId()) {
            $this->addColumn(
                'tpl_eventsmanager_event_event_name', 
                array(
                    'header'    => Mage::helper('tpl_eventsmanager')->__('Event Name in %s', $this->_getStore()->getName()),
                    'align'     => 'left',
                    'index'     => 'tpl_eventsmanager_event_event_name',
                )
            );
        }

        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('tpl_eventsmanager')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('tpl_eventsmanager')->__('Enabled'),
                    '0' => Mage::helper('tpl_eventsmanager')->__('Disabled'),
                )
            )
        );
        $this->addColumn(
            'from_date',
            array(
                'header' => Mage::helper('tpl_eventsmanager')->__('From Date'),
                'index'  => 'from_date',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'end_date',
            array(
                'header' => Mage::helper('tpl_eventsmanager')->__('End Date'),
                'index'  => 'end_date',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'event_level',
            array(
                'header' => Mage::helper('tpl_eventsmanager')->__('Event Level'),
                'index'  => 'event_level',
                'type'  => 'options',
                'options' => Mage::helper('tpl_eventsmanager')->convertOptions(
                    Mage::getModel('eav/config')->getAttribute('tpl_eventsmanager_event', 'event_level')->getSource()->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'event_type',
            array(
                'header' => Mage::helper('tpl_eventsmanager')->__('Event Type'),
                'index'  => 'event_type',
                'type'  => 'options',
                'options' => Mage::helper('tpl_eventsmanager')->convertOptions(
                    Mage::getModel('eav/config')->getAttribute('tpl_eventsmanager_event', 'event_type')->getSource()->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'cancel_event',
            array(
                'header' => Mage::helper('tpl_eventsmanager')->__('Cancel Event'),
                'index'  => 'cancel_event',
                'type'    => 'options',
                    'options'    => array(
                    '1' => Mage::helper('tpl_eventsmanager')->__('Yes'),
                    '0' => Mage::helper('tpl_eventsmanager')->__('No'),
                )

            )
        );
        $this->addColumn(
            'url_key',
            array(
                'header' => Mage::helper('tpl_eventsmanager')->__('URL key'),
                'index'  => 'url_key',
            )
        );
        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('tpl_eventsmanager')->__('Created at'),
                'index'  => 'created_at',
                'width'  => '120px',
                'type'   => 'datetime',
            )
        );
        $this->addColumn(
            'updated_at',
            array(
                'header'    => Mage::helper('tpl_eventsmanager')->__('Updated at'),
                'index'     => 'updated_at',
                'width'     => '120px',
                'type'      => 'datetime',
            )
        );
        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('tpl_eventsmanager')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('tpl_eventsmanager')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('tpl_eventsmanager')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('tpl_eventsmanager')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('tpl_eventsmanager')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * get the selected store
     *
     * @access protected
     * @return Mage_Core_Model_Store
     * @author TPL
     */
    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return Tpl_EventsManager_Block_Adminhtml_Event_Grid
     * @author TPL
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('event');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('tpl_eventsmanager')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('tpl_eventsmanager')->__('Are you sure?')
            )
        );
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label'      => Mage::helper('tpl_eventsmanager')->__('Change status'),
                'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                        'name'   => 'status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('tpl_eventsmanager')->__('Status'),
                        'values' => array(
                            '1' => Mage::helper('tpl_eventsmanager')->__('Enabled'),
                            '0' => Mage::helper('tpl_eventsmanager')->__('Disabled'),
                        )
                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'event_level',
            array(
                'label'      => Mage::helper('tpl_eventsmanager')->__('Change Event Level'),
                'url'        => $this->getUrl('*/*/massEventLevel', array('_current'=>true)),
                'additional' => array(
                    'flag_event_level' => array(
                        'name'   => 'flag_event_level',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('tpl_eventsmanager')->__('Event Level'),
                        'values' => Mage::getModel('eav/config')->getAttribute('tpl_eventsmanager_event', 'event_level')
                            ->getSource()->getAllOptions(true),

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'event_type',
            array(
                'label'      => Mage::helper('tpl_eventsmanager')->__('Change Event Type'),
                'url'        => $this->getUrl('*/*/massEventType', array('_current'=>true)),
                'additional' => array(
                    'flag_event_type' => array(
                        'name'   => 'flag_event_type',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('tpl_eventsmanager')->__('Event Type'),
                        'values' => Mage::getModel('eav/config')->getAttribute('tpl_eventsmanager_event', 'event_type')
                            ->getSource()->getAllOptions(true),

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'is_invitable',
            array(
                'label'      => Mage::helper('tpl_eventsmanager')->__('Change Allow Invitation'),
                'url'        => $this->getUrl('*/*/massIsInvitable', array('_current'=>true)),
                'additional' => array(
                    'flag_is_invitable' => array(
                        'name'   => 'flag_is_invitable',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('tpl_eventsmanager')->__('Allow Invitation'),
                        'values' => array(
                                '1' => Mage::helper('tpl_eventsmanager')->__('Yes'),
                                '0' => Mage::helper('tpl_eventsmanager')->__('No'),
                            )

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'cancel_event',
            array(
                'label'      => Mage::helper('tpl_eventsmanager')->__('Change Cancel Event'),
                'url'        => $this->getUrl('*/*/massCancelEvent', array('_current'=>true)),
                'additional' => array(
                    'flag_cancel_event' => array(
                        'name'   => 'flag_cancel_event',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('tpl_eventsmanager')->__('Cancel Event'),
                        'values' => array(
                                '1' => Mage::helper('tpl_eventsmanager')->__('Yes'),
                                '0' => Mage::helper('tpl_eventsmanager')->__('No'),
                            )

                    )
                )
            )
        );
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param Tpl_EventsManager_Model_Event
     * @return string
     * @author TPL
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string
     * @author TPL
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
}
