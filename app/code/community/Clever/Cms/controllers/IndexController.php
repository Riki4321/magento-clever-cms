<?php

require_once 'Mage/Cms/controllers/IndexController.php';

class Clever_Cms_IndexController extends Mage_Cms_IndexController
{
    public function indexAction($coreRoute = null)
    {
        $store = Mage::app()->getStore();
        $pageId = Mage::getResourceModel('cms/page')->getStoreRootId($store->getId());
        if (!$pageId) {
            $pageId = Mage::getResourceModel('cms/page')->getStoreRootId(Mage_Catalog_Model_Abstract::DEFAULT_STORE_ID);
        }
        if (!Mage::helper('cms/page')->renderPage($this, $pageId)) {
            $this->_forward('defaultIndex');
        }
    }
}