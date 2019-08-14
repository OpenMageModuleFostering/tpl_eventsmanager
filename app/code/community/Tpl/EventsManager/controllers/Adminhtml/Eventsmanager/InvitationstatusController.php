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
 * Invitation Status admin controller
 *
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
class Tpl_EventsManager_Adminhtml_Eventsmanager_InvitationstatusController extends Mage_Adminhtml_Controller_Action
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
     * init the invitation status
     *
     * @access protected 
     * @return Tpl_EventsManager_Model_Invitationstatus
     * @author TPL
     */
    protected function _initInvitationstatus()
    {
        $this->_title($this->__('Events Manager'))
             ->_title($this->__('Manage Invitations Status'));

        $invitationstatusId  = (int) $this->getRequest()->getParam('id');
        $invitationstatus    = Mage::getModel('tpl_eventsmanager/invitationstatus')
            ->setStoreId($this->getRequest()->getParam('store', 0));

        if ($invitationstatusId) {
            $invitationstatus->load($invitationstatusId);
        }
        Mage::register('current_invitationstatus', $invitationstatus);
        return $invitationstatus;
    }

    /**
     * default action for invitationstatus controller
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function indexAction()
    {
        $this->_title($this->__('Events Manager'))
             ->_title($this->__('Manage Invitations Status'));
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * new invitationstatus action
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
     * edit invitationstatus action
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function editAction()
    {
        $invitationstatusId  = (int) $this->getRequest()->getParam('id');
        $invitationstatus    = $this->_initInvitationstatus();
        if ($invitationstatusId && !$invitationstatus->getId()) {
            $this->_getSession()->addError(
                Mage::helper('tpl_eventsmanager')->__('This invitation status no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        if ($data = Mage::getSingleton('adminhtml/session')->getInvitationstatusData(true)) {
            $invitationstatus->setData($data);
        }
        $this->_title($invitationstatus->getEventName());
        Mage::dispatchEvent(
            'tpl_eventsmanager_invitationstatus_edit_action',
            array('invitationstatus' => $invitationstatus)
        );
        $this->loadLayout();
        if ($invitationstatus->getId()) {
            if (!Mage::app()->isSingleStoreMode() && ($switchBlock = $this->getLayout()->getBlock('store_switcher'))) {
                $switchBlock->setDefaultStoreName(Mage::helper('tpl_eventsmanager')->__('Default Values'))
                    ->setWebsiteIds($invitationstatus->getWebsiteIds())
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
     * save invitation status action
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function saveAction()
    {
        $storeId        = $this->getRequest()->getParam('store');
        $redirectBack   = $this->getRequest()->getParam('back', false);
        $invitationstatusId   = $this->getRequest()->getParam('id');
        $isEdit         = (int)($this->getRequest()->getParam('id') != null);
        $data = $this->getRequest()->getPost();
        if ($data) {
            $invitationstatus     = $this->_initInvitationstatus();
            $invitationstatusData = $this->getRequest()->getPost('invitationstatus', array());
            $invitationstatus->addData($invitationstatusData);
            $invitationstatus->setAttributeSetId($invitationstatus->getDefaultAttributeSetId());
            if ($useDefaults = $this->getRequest()->getPost('use_default')) {
                foreach ($useDefaults as $attributeCode) {
                    $invitationstatus->setData($attributeCode, false);
                }
            }
            try {
                $invitationstatus->save();
                $invitationstatusId = $invitationstatus->getId();
                $this->_getSession()->addSuccess(
                    Mage::helper('tpl_eventsmanager')->__('Invitation Status was saved')
                );
            } catch (Mage_Core_Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage())
                    ->setInvitationstatusData($invitationstatusData);
                $redirectBack = true;
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError(
                    Mage::helper('tpl_eventsmanager')->__('Error saving invitation status')
                )
                ->setInvitationstatusData($invitationstatusData);
                $redirectBack = true;
            }
        }
        if ($redirectBack) {
            $this->_redirect(
                '*/*/edit',
                array(
                    'id'    => $invitationstatusId,
                    '_current'=>true
                )
            );
        } else {
            $this->_redirect('*/*/', array('store'=>$storeId));
        }
    }

    /**
     * delete invitation status
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $invitationstatus = Mage::getModel('tpl_eventsmanager/invitationstatus')->load($id);
            try {
                $invitationstatus->delete();
                $this->_getSession()->addSuccess(
                    Mage::helper('tpl_eventsmanager')->__('The invitations status has been deleted.')
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
     * mass delete invitations status
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function massDeleteAction()
    {
        $invitationstatusIds = $this->getRequest()->getParam('invitationstatus');
        if (!is_array($invitationstatusIds)) {
            $this->_getSession()->addError($this->__('Please select invitations status.'));
        } else {
            try {
                foreach ($invitationstatusIds as $invitationstatusId) {
                    $invitationstatus = Mage::getSingleton('tpl_eventsmanager/invitationstatus')->load($invitationstatusId);
                    Mage::dispatchEvent(
                        'tpl_eventsmanager_controller_invitationstatus_delete',
                        array('invitationstatus' => $invitationstatus)
                    );
                    $invitationstatus->delete();
                }
                $this->_getSession()->addSuccess(
                    Mage::helper('tpl_eventsmanager')->__('Total of %d record(s) have been deleted.', count($invitationstatusIds))
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
        $invitationstatusIds = $this->getRequest()->getParam('invitationstatus');
        if (!is_array($invitationstatusIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tpl_eventsmanager')->__('Please select invitations status.')
            );
        } else {
            try {
                foreach ($invitationstatusIds as $invitationstatusId) {
                $invitationstatus = Mage::getSingleton('tpl_eventsmanager/invitationstatus')->load($invitationstatusId)
                    ->setStatus($this->getRequest()->getParam('status'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d invitations status were successfully updated.', count($invitationstatusIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('tpl_eventsmanager')->__('There was an error updating invitations status.')
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
        return Mage::getSingleton('admin/session')->isAllowed('tpl_eventsmanager/invitationstatus');
    }

    /**
     * Export invitationsstatus in CSV format
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function exportCsvAction()
    {
        $fileName   = 'invitationsstatus.csv';
        $content    = $this->getLayout()->createBlock('tpl_eventsmanager/adminhtml_invitationstatus_grid')
            ->getCsvFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export invitations status in Excel format
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function exportExcelAction()
    {
        $fileName   = 'invitationstatus.xls';
        $content    = $this->getLayout()->createBlock('tpl_eventsmanager/adminhtml_invitationstatus_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export invitations status in XML format
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function exportXmlAction()
    {
        $fileName   = 'invitationstatus.xml';
        $content    = $this->getLayout()->createBlock('tpl_eventsmanager/adminhtml_invitationstatus_grid')
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
     * mass Invitation Status change
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function massEventInvitationStatusAction()
    {
        $invitationstatusIds = (array)$this->getRequest()->getParam('invitationstatus');
        $storeId       = (int)$this->getRequest()->getParam('store', 0);
        $flag          = (int)$this->getRequest()->getParam('flag_event_invitation_status');
        if ($flag == 2) {
            $flag = 0;
        }
        try {
            foreach ($invitationstatusIds as $invitationstatusId) {
                $invitationstatus = Mage::getSingleton('tpl_eventsmanager/invitationstatus')
                    ->setStoreId($storeId)
                    ->load($invitationstatusId);
                $invitationstatus->setEventInvitationStatus($flag)->save();
            }
            $this->_getSession()->addSuccess(
                Mage::helper('tpl_eventsmanager')->__('Total of %d record(s) have been updated.', count($invitationstatusIds))
            );
        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('tpl_eventsmanager')->__('An error occurred while updating the invitations status.')
            );
        }
        $this->_redirect('*/*/', array('store'=> $storeId));
    }
}
