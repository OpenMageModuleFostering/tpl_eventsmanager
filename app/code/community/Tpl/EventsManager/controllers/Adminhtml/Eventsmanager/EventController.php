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
 * Event admin controller
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Adminhtml_Eventsmanager_EventController extends Mage_Adminhtml_Controller_Action
{
    /**
     * constructor - set the used module name
     *
     * @access protected
     * @return void
     * @see Mage_Core_Controller_Varien_Action::_construct()
     * @author TPL
     */
    protected function _construct()
    {
        $this->setUsedModuleName('Tpl_EventsManager');
    }

    /**
     * init the event
     *
     * @access protected 
     * @return Tpl_EventsManager_Model_Event
     * @author TPL
     */
    protected function _initEvent()
    {
        $this->_title($this->__('Events Manager'))
             ->_title($this->__('Manage Events'));

        $eventId  = (int) $this->getRequest()->getParam('id');
        $event    = Mage::getModel('tpl_eventsmanager/event')
            ->setStoreId($this->getRequest()->getParam('store', 0));

        if ($eventId) {
            $event->load($eventId);
        }
        Mage::register('current_event', $event);
        return $event;
    }

    /**
     * default action for event controller
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function indexAction()
    {
        $this->_title($this->__('Events Manager'))
             ->_title($this->__('Manage Events'));
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * new event action
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * edit event action
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function editAction()
    {
        $eventId  = (int) $this->getRequest()->getParam('id');
        $event    = $this->_initEvent();
        if ($eventId && !$event->getId()) {
            $this->_getSession()->addError(
                Mage::helper('tpl_eventsmanager')->__('This event no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        if ($data = Mage::getSingleton('adminhtml/session')->getEventData(true)) {
            $event->setData($data);
        }
        $this->_title($event->getEventName());
        Mage::dispatchEvent(
            'tpl_eventsmanager_event_edit_action',
            array('event' => $event)
        );
        $this->loadLayout();
        if ($event->getId()) {
            if (!Mage::app()->isSingleStoreMode() && ($switchBlock = $this->getLayout()->getBlock('store_switcher'))) {
                $switchBlock->setDefaultStoreName(Mage::helper('tpl_eventsmanager')->__('Default Values'))
                    ->setWebsiteIds($event->getWebsiteIds())
                    ->setSwitchUrl(
                        $this->getUrl(
                            '*/*/*',
                            array(
                                '_current'=>true,
                                'active_tab'=>null,
                                'tab' => null,
                                'store'=>null
                            )
                        )
                    );
            }
        } else {
            $this->getLayout()->getBlock('left')->unsetChild('store_switcher');
        }
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
    }

    /**
     * save event action
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function saveAction()
    {
        $storeId        = $this->getRequest()->getParam('store');
        $redirectBack   = $this->getRequest()->getParam('back', false);
        $eventId   = $this->getRequest()->getParam('id');
        $isEdit         = (int)($this->getRequest()->getParam('id') != null);
        $data = $this->getRequest()->getPost();
        if ($data) {
            $event     = $this->_initEvent();
            $eventData = $this->getRequest()->getPost('event', array());
            $event->addData($eventData);
            $event->setAttributeSetId($event->getDefaultAttributeSetId());
                $products = $this->getRequest()->getPost('products', -1);
                if ($products != -1) {
                    $event->setProductsData(
                        Mage::helper('adminhtml/js')->decodeGridSerializedInput($products)
                    );
                }
            if ($useDefaults = $this->getRequest()->getPost('use_default')) {
                foreach ($useDefaults as $attributeCode) {
                    $event->setData($attributeCode, false);
                }
            }
            try {
                $event->save();
                $eventId = $event->getId();
                $this->_getSession()->addSuccess(
                    Mage::helper('tpl_eventsmanager')->__('Event was saved')
                );
            } catch (Mage_Core_Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage())
                    ->setEventData($eventData);
                $redirectBack = true;
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError(
                    Mage::helper('tpl_eventsmanager')->__('Error saving event')
                )
                ->setEventData($eventData);
                $redirectBack = true;
            }
        }
        if ($redirectBack) {
            $this->_redirect(
                '*/*/edit',
                array(
                    'id'    => $eventId,
                    '_current'=>true
                )
            );
        } else {
            $this->_redirect('*/*/', array('store'=>$storeId));
        }
    }

    /**
     * delete event
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $event = Mage::getModel('tpl_eventsmanager/event')->load($id);
            try {
                $event->delete();
                $this->_getSession()->addSuccess(
                    Mage::helper('tpl_eventsmanager')->__('The events has been deleted.')
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->getResponse()->setRedirect(
            $this->getUrl('*/*/', array('store'=>$this->getRequest()->getParam('store')))
        );
    }

    /**
     * mass delete events
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function massDeleteAction()
    {
        $eventIds = $this->getRequest()->getParam('event');
        if (!is_array($eventIds)) {
            $this->_getSession()->addError($this->__('Please select events.'));
        } else {
            try {
                foreach ($eventIds as $eventId) {
                    $event = Mage::getSingleton('tpl_eventsmanager/event')->load($eventId);
                    Mage::dispatchEvent(
                        'tpl_eventsmanager_controller_event_delete',
                        array('event' => $event)
                    );
                    $event->delete();
                }
                $this->_getSession()->addSuccess(
                    Mage::helper('tpl_eventsmanager')->__('Total of %d record(s) have been deleted.', count($eventIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function massStatusAction()
    {
        $eventIds = $this->getRequest()->getParam('event');
        if (!is_array($eventIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tpl_eventsmanager')->__('Please select events.')
            );
        } else {
            try {
                foreach ($eventIds as $eventId) {
                $event = Mage::getSingleton('tpl_eventsmanager/event')->load($eventId)
                    ->setStatus($this->getRequest()->getParam('status'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d events were successfully updated.', count($eventIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('tpl_eventsmanager')->__('There was an error updating events.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * restrict access
     *
     * @access protected
     * @return bool
     * @see Mage_Adminhtml_Controller_Action::_isAllowed()
     * @author TPL
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('tpl_eventsmanager/event');
    }

    /**
     * Export events in CSV format
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function exportCsvAction()
    {
        $fileName   = 'events.csv';
        $content    = $this->getLayout()->createBlock('tpl_eventsmanager/adminhtml_event_grid')
            ->getCsvFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export events in Excel format
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function exportExcelAction()
    {
        $fileName   = 'event.xls';
        $content    = $this->getLayout()->createBlock('tpl_eventsmanager/adminhtml_event_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export events in XML format
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function exportXmlAction()
    {
        $fileName   = 'event.xml';
        $content    = $this->getLayout()->createBlock('tpl_eventsmanager/adminhtml_event_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * wysiwyg editor action
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function wysiwygAction()
    {
        $elementId     = $this->getRequest()->getParam('element_id', md5(microtime()));
        $storeId       = $this->getRequest()->getParam('store_id', 0);
        $storeMediaUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);

        $content = $this->getLayout()->createBlock(
            'tpl_eventsmanager/adminhtml_eventsmanager_helper_form_wysiwyg_content',
            '',
            array(
                'editor_element_id' => $elementId,
                'store_id'          => $storeId,
                'store_media_url'   => $storeMediaUrl,
            )
        );
        $this->getResponse()->setBody($content->toHtml());
    }

    /**
     * mass Event Level change
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function massEventLevelAction()
    {
        $eventIds = (array)$this->getRequest()->getParam('event');
        $storeId       = (int)$this->getRequest()->getParam('store', 0);
        $flag          = (int)$this->getRequest()->getParam('flag_event_level');
        if ($flag == 2) {
            $flag = 0;
        }
        try {
            foreach ($eventIds as $eventId) {
                $event = Mage::getSingleton('tpl_eventsmanager/event')
                    ->setStoreId($storeId)
                    ->load($eventId);
                $event->setEventLevel($flag)->save();
            }
            $this->_getSession()->addSuccess(
                Mage::helper('tpl_eventsmanager')->__('Total of %d record(s) have been updated.', count($eventIds))
            );
        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('tpl_eventsmanager')->__('An error occurred while updating the events.')
            );
        }
        $this->_redirect('*/*/', array('store'=> $storeId));
    }

    /**
     * mass Event Type change
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function massEventTypeAction()
    {
        $eventIds = (array)$this->getRequest()->getParam('event');
        $storeId       = (int)$this->getRequest()->getParam('store', 0);
        $flag          = (int)$this->getRequest()->getParam('flag_event_type');
        if ($flag == 2) {
            $flag = 0;
        }
        try {
            foreach ($eventIds as $eventId) {
                $event = Mage::getSingleton('tpl_eventsmanager/event')
                    ->setStoreId($storeId)
                    ->load($eventId);
                $event->setEventType($flag)->save();
            }
            $this->_getSession()->addSuccess(
                Mage::helper('tpl_eventsmanager')->__('Total of %d record(s) have been updated.', count($eventIds))
            );
        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('tpl_eventsmanager')->__('An error occurred while updating the events.')
            );
        }
        $this->_redirect('*/*/', array('store'=> $storeId));
    }

    /**
     * mass Allow Invitation change
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function massIsInvitableAction()
    {
        $eventIds = (array)$this->getRequest()->getParam('event');
        $storeId       = (int)$this->getRequest()->getParam('store', 0);
        $flag          = (int)$this->getRequest()->getParam('flag_is_invitable');
        if ($flag == 2) {
            $flag = 0;
        }
        try {
            foreach ($eventIds as $eventId) {
                $event = Mage::getSingleton('tpl_eventsmanager/event')
                    ->setStoreId($storeId)
                    ->load($eventId);
                $event->setIsInvitable($flag)->save();
            }
            $this->_getSession()->addSuccess(
                Mage::helper('tpl_eventsmanager')->__('Total of %d record(s) have been updated.', count($eventIds))
            );
        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('tpl_eventsmanager')->__('An error occurred while updating the events.')
            );
        }
        $this->_redirect('*/*/', array('store'=> $storeId));
    }

    /**
     * mass Cancel Event change
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function massCancelEventAction()
    {
        $eventIds = (array)$this->getRequest()->getParam('event');
        $storeId       = (int)$this->getRequest()->getParam('store', 0);
        $flag          = (int)$this->getRequest()->getParam('flag_cancel_event');
        if ($flag == 2) {
            $flag = 0;
        }
        try {
            foreach ($eventIds as $eventId) {
                $event = Mage::getSingleton('tpl_eventsmanager/event')
                    ->setStoreId($storeId)
                    ->load($eventId);
                $event->setCancelEvent($flag)->save();
            }
            $this->_getSession()->addSuccess(
                Mage::helper('tpl_eventsmanager')->__('Total of %d record(s) have been updated.', count($eventIds))
            );
        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('tpl_eventsmanager')->__('An error occurred while updating the events.')
            );
        }
        $this->_redirect('*/*/', array('store'=> $storeId));
    }

    /**
     * get grid of products action
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function productsAction()
    {
        $this->_initEvent();
        $this->loadLayout();
        $this->getLayout()->getBlock('event.edit.tab.product')
            ->setEventProducts($this->getRequest()->getPost('event_products', null));
        $this->renderLayout();
    }

    /**
     * get grid of products action
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function productsgridAction()
    {
        $this->_initEvent();
        $this->loadLayout();
        $this->getLayout()->getBlock('event.edit.tab.product')
            ->setEventProducts($this->getRequest()->getPost('event_products', null));
        $this->renderLayout();
    }
}
