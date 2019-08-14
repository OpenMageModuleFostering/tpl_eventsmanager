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
 * Event comments admin grid block
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Block_Adminhtml_Event_Comment_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('eventCommentGrid');
        $this->setDefaultSort('ct_comment_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return Tpl_EventsManager_Block_Adminhtml_Event_Comment_Grid
     * @author TPL
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('tpl_eventsmanager/event_comment_event_collection');
        $collection->addStoreData();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return Tpl_EventsManager_Block_Adminhtml_Event_Comment_Grid
     * @author TPL
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'ct_comment_id',
            array(
                'header'        => Mage::helper('tpl_eventsmanager')->__('Id'),
                'index'         => 'ct_comment_id',
                'type'          => 'number',
                'filter_index'  => 'ct.comment_id',
            )
        );
        $this->addColumn(
            'event_name',
            array(
                'header'        => Mage::helper('tpl_eventsmanager')->__('Event Name'),
                'index'         => 'event_name',
                'filter_index'  => 'event_name',
            )
        );
        $this->addColumn(
            'ct_title',
            array(
                'header'        => Mage::helper('tpl_eventsmanager')->__('Comment Title'),
                'index'         => 'ct_title',
                'filter_index'  => 'ct.title',
            )
        );
        $this->addColumn(
            'ct_name',
            array(
                'header'        => Mage::helper('tpl_eventsmanager')->__('Poster name'),
                'index'         => 'ct_name',
                'filter_index'  => 'ct.name',
            )
        );
        $this->addColumn(
            'ct_email',
            array(
                'header'        => Mage::helper('tpl_eventsmanager')->__('Poster email'),
                'index'         => 'ct_email',
                'filter_index'  => 'ct.email',
            )
        );
        $this->addColumn(
            'ct_status',
            array(
                'header'        => Mage::helper('tpl_eventsmanager')->__('Status'),
                'index'         => 'ct_status',
                'filter_index'  => 'ct.status',
                'type'          => 'options',
                'options'       => array(
                    Tpl_EventsManager_Model_Event_Comment::STATUS_PENDING  =>
                        Mage::helper('tpl_eventsmanager')->__('Pending'),
                    Tpl_EventsManager_Model_Event_Comment::STATUS_APPROVED =>
                        Mage::helper('tpl_eventsmanager')->__('Approved'),
                    Tpl_EventsManager_Model_Event_Comment::STATUS_REJECTED =>
                        Mage::helper('tpl_eventsmanager')->__('Rejected'),
                )
            )
        );
        $this->addColumn(
            'ct_created_at',
            array(
                'header'        => Mage::helper('tpl_eventsmanager')->__('Created at'),
                'index'         => 'ct_created_at',
                'width'         => '120px',
                'type'          => 'datetime',
                'filter_index'  => 'ct.created_at',
            )
        );
        $this->addColumn(
            'ct_updated_at',
            array(
                'header'        => Mage::helper('tpl_eventsmanager')->__('Updated at'),
                'index'         => 'ct_updated_at',
                'width'         => '120px',
                'type'          => 'datetime',
                'filter_index'  => 'ct.updated_at',
            )
        );
        if (!Mage::app()->isSingleStoreMode() && !$this->_isExport) {
            $this->addColumn(
                'stores',
                array(
                    'header'     => Mage::helper('tpl_eventsmanager')->__('Store Views'),
                    'index'      => 'stores',
                    'type'       => 'store',
                    'store_all'  => true,
                    'store_view' => true,
                    'sortable'   => false,
                    'filter_condition_callback' => array($this, '_filterStoreCondition'),
                )
            );
        }
        $this->addColumn(
            'action',
            array(
                'header'  => Mage::helper('tpl_eventsmanager')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getCtCommentId',
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
     * prepare mass action
     *
     * @access protected
     * @return Tpl_EventsManager_Block_Adminhtml_Event_Grid
     * @author TPL
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('ct_comment_id');
        $this->setMassactionIdFilter('ct.comment_id');
        $this->setMassactionIdFieldOnlyIndexValue(true);
        $this->getMassactionBlock()->setFormFieldName('comment');
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
                'label' => Mage::helper('tpl_eventsmanager')->__('Change status'),
                'url'   => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                            'name' => 'status',
                            'type' => 'select',
                            'class' => 'required-entry',
                            'label' => Mage::helper('tpl_eventsmanager')->__('Status'),
                            'values' => array(
                                Tpl_EventsManager_Model_Event_Comment::STATUS_PENDING  =>
                                    Mage::helper('tpl_eventsmanager')->__('Pending'),
                                Tpl_EventsManager_Model_Event_Comment::STATUS_APPROVED =>
                                    Mage::helper('tpl_eventsmanager')->__('Approved'),
                                Tpl_EventsManager_Model_Event_Comment::STATUS_REJECTED =>
                                    Mage::helper('tpl_eventsmanager')->__('Rejected'),
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
     * @param Tpl_EventsManager_Model_Event_Comment
     * @return string
     * @author TPL
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getCtCommentId()));
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

    /**
     * filter store column
     *
     * @access protected
     * @param Tpl_EventsManager_Model_Resource_Event_Comment_Collection $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return Tpl_EventsManager_Block_Adminhtml_Event_Comment_Grid
     * @author TPL
     */
    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->setStoreFilter($value);
        return $this;
    }
}
