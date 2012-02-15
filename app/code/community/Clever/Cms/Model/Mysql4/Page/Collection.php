<?php

class Clever_Cms_Model_Mysql4_Page_Collection extends Mage_Cms_Model_Mysql4_Page_Collection
{
    protected function _construct()
    {
        $this->_init('cms/page');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
        $this->_map['fields']['store']   = 'main_table.store_id';
    }

    public function addIdFilter($pageIds)
    {
        if (is_array($pageIds)) {
            if (empty($pageIds)) {
                $condition = '';
            } else {
                $condition = array('in' => $pageIds);
            }
        } elseif (is_numeric($pageIds)) {
            $condition = $pageIds;
        } elseif (is_string($pageIds)) {
            $ids = explode(',', $pageIds);
            if (empty($ids)) {
                $condition = $pageIds;
            } else {
                $condition = array('in' => $ids);
            }
        }
        $this->addFieldToFilter('page_id', $condition);

        return $this;
    }

    public function addPermissionsFilter($customerGroupId)
    {
        $this->getSelect()->join(
            array('p' => $this->getTable('cms/page_permission')),
            'p.page_id = main_table.page_id',
            ''
        )->where('p.customer_group_id = ?', $customerGroupId);

        return $this;
    }

    public function addIncludeInMenuFilter()
    {
        return $this->addFieldToFilter('include_in_menu', '1');
    }

    public function addAllChildrenFilter(Clever_Cms_Model_Page $page)
    {
        $this->addFieldToFilter('identifier', array('like' => $page->getIdentifier() . '/%'))
            ->addFieldToFilter('store_id', $page->getStoreId());

        return $this;
    }

    protected function _afterLoad()
    {
        if ($this->_previewFlag) {
            $items = $this->getColumnValues('page_id');
            if (count($items)) {
                $select = $this->getConnection()->select()
                        ->from($this->getTable('cms/page'))
                        ->where($this->getTable('cms/page').'.store_id IN (?)', $items);
                if ($result = $this->getConnection()->fetchPairs($select)) {
                    foreach ($this as $item) {
                        if (!isset($result[$item->getData('page_id')])) {
                            continue;
                        }
                        if ($result[$item->getData('page_id')] == 0) {
                            $stores = Mage::app()->getStores(false, true);
                            $storeId = current($stores)->getId();
                            $storeCode = key($stores);
                        } else {
                            $storeId = $result[$item->getData('page_id')];
                            $storeCode = Mage::app()->getStore($storeId)->getCode();
                        }
                        $item->setData('_first_store_id', $storeId);
                        $item->setData('store_code', $storeCode);
                    }
                }
            }
        }

        parent::_afterLoad();
    }

    protected function _renderFiltersBefore()
    {
        return $this;
    }
}