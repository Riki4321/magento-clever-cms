<?php

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$tablePermission = $installer->getTable('cms_page_permission');
$tableStore = $installer->getTable('core_store');
$tablePageTree = $installer->getTable('cms_page_tree');
$tableCustomerGroup = $installer->getTable('customer_group');
$installer->run("
DROP TABLE IF EXISTS `{$tablePermission}`;
CREATE TABLE `{$tablePermission}` (
`permission_id` MEDIUMINT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`store_id` SMALLINT( 5 ) UNSIGNED NOT NULL ,
`customer_group_id` SMALLINT( 3 ) UNSIGNED NOT NULL ,
`page_id` SMALLINT( 6 ) NOT NULL
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci;

ALTER TABLE `{$tablePermission}` ADD INDEX ( `store_id` );
ALTER TABLE `{$tablePermission}` ADD INDEX ( `customer_group_id` );
ALTER TABLE `{$tablePermission}` ADD INDEX ( `page_id` );
ALTER TABLE `{$tablePermission}` ADD UNIQUE ( `store_id`, `customer_group_id`, `page_id` );

ALTER TABLE `{$tablePermission}` ADD FOREIGN KEY ( `store_id` ) REFERENCES `{$tableStore}` (
`store_id`
) ON DELETE CASCADE ON UPDATE CASCADE ;
ALTER TABLE `{$tablePermission}` ADD FOREIGN KEY ( `customer_group_id` ) REFERENCES `{$tableCustomerGroup}` (
`customer_group_id`
) ON DELETE CASCADE ON UPDATE CASCADE ;
ALTER TABLE `{$tablePermission}` ADD FOREIGN KEY ( `page_id` ) REFERENCES `{$tablePageTree}` (
`page_id`
) ON DELETE CASCADE ON UPDATE CASCADE ;
");

foreach (Mage::getModel('cms/page')->getCollection() as $page) {
    foreach (Mage::getModel('customer/group')->getCollection() as $customerGroup) {
        $storeId = $page->getStoreId();
        $customerGroupId = $customerGroup->getId();
        $pageId = $page->getId();
        $installer->run("INSERT INTO `{$tablePermission}` (`store_id`, `customer_group_id`, `page_id`) VALUES ('{$storeId}', '{$customerGroupId}', '{$pageId}');");
    }
}

$installer->endSetup();