<?php

class Clever_Cms_Block_Page extends Mage_Cms_Block_Page
{
    protected function _prepareLayout()
    {
        $page = $this->getPage();
        /* @var $page Clever_Cms_Model_Page */

        // show breadcrumbs
        if (!$page->isRoot()
            && Mage::getStoreConfig('web/default/show_cms_breadcrumbs')
            && ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs'))
            && ($page->getIdentifier() !== Mage::getStoreConfig('web/default/cms_home_page'))
            && ($page->getIdentifier() !== Mage::getStoreConfig('web/default/cms_no_route')))
        {
            $breadcrumbs->addCrumb('home', array('label' => Mage::helper('cms')->__('Home'), 'title' => Mage::helper('cms')->__('Go to Home Page'), 'link' => Mage::getBaseUrl()));
            foreach ($page->getParentIds() as $k => $parentId) {
                $parent = Mage::getModel('cms/page')->load($parentId);
		if ($parent->getId() && $parent->getParentId() && $parent->getIdentifier() != Mage::getStoreConfig('cms/clever/root_cms_page')) {
                    $breadcrumbs->addCrumb('cms_page_' . $k, array('label' => $parent->getTitle(), 'title' => $parent->getTitle(), 'link' => rtrim(Mage::getBaseUrl() . $parent->getIdentifier(), '/') . '/'));
                }
            }
            $breadcrumbs->addCrumb('cms_page', array('label' => $page->getTitle(), 'title' => $page->getTitle()));
        }

        $root = $this->getLayout()->getBlock('root');
        if ($root) {
            $root->addBodyClass('cms-' . $page->getIdentifier());
        }

        $head = $this->getLayout()->getBlock('head');
        if ($head) {
            $head->setTitle($page->getTitle());
            $head->setKeywords($page->getMetaKeywords());
            $head->setDescription($page->getMetaDescription());
        }

        return $this;
    }
}
