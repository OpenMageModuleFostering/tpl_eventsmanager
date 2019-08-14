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
 * Event - product controller
 * @category    Tpl
 * @package     Tpl_EventsManager
 * @author      TPL
 */
require_once ("Mage/Adminhtml/controllers/Catalog/ProductController.php");
class Tpl_EventsManager_Adminhtml_Eventsmanager_Event_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
{
    /**
     * construct
     *
     * @access protected
     * @return void
     * @author TPL
     */
    protected function _construct()
    {
        // Define module dependent translate
        $this->setUsedModuleName('Tpl_EventsManager');
    }

    /**
     * events in the catalog page
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function eventsAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.event')
            ->setProductEvents($this->getRequest()->getPost('product_events', null));
        $this->renderLayout();
    }

    /**
     * events grid in the catalog page
     *
     * @access public
     * @return void
     * @author TPL
     */
    public function eventsGridAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.event')
            ->setProductEvents($this->getRequest()->getPost('product_events', null));
        $this->renderLayout();
    }
}
