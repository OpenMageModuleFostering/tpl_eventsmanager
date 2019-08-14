<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Tpl_EventsManager_Model_Observer {
    public function addItemsToTopmenuItems($observer) {
        
       if(Mage::getStoreConfig('tpl_eventsmanager/customsettings/events_menu', Mage::app()->getStore())){
        $menu = $observer->getMenu();
        $tree = $menu->getTree();
        $action = Mage::app()->getFrontController()->getAction()->getFullActionName();

        $nodeId = 'event_menu';
        $data = array(
            'name' => Mage::helper('tpl_eventsmanager')->__('Events'),
            'id' => $nodeId,
            'url' => Mage::getUrl('tpl_eventsmanager/event/index'),
            'is_active' => ($action == 'tpl_eventsmanager_event_index')
        );
        $node = new Varien_Data_Tree_Node($data, 'id', $tree, $menu);
        $menu->addChild($node);
        return $this;
       }
    }
}
