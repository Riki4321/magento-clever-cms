<?php

require_once 'Mage/Cms/controllers/IndexController.php';

class JR_CleverCms_Cms_IndexController extends Mage_Cms_IndexController
{
    public function indexAction($coreRoute = null)
    {
        $store = Mage::app()->getStore();
        $pageId = Mage::getResourceModel('cms/page')->getStoreRootId($store->getId());
        if (!$pageId) {
            $pageId = Mage::getResourceModel('cms/page')->getStoreRootId(0);
        }
        if (!Mage::helper('cms/page')->renderPage($this, $pageId)) {
            $this->_forward('defaultIndex');
        }
    }
}