<?php

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

Mage::app()->reinitStores(); // needed to have $defaultStoreViewId
$tablePage = $this->getTable('cms_page');
$tablePageStore = $this->getTable('cms_page_store');
$tablePageTree = $this->getTable('cms_page_tree');
$tableCoreStore = $this->getTable('core_store');
$defaultStoreViewId = (int) Mage::app()->getDefaultStoreView()->getId();
$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS `{$tablePageTree}`;
CREATE TABLE `{$tablePageTree}` LIKE `{$tablePage}`;
INSERT INTO `{$tablePageTree}` (SELECT `{$tablePage}`.* FROM `{$tablePage}` INNER JOIN `{$tablePageStore}` USING (`page_id`) WHERE `{$tablePageStore}`.`store_id` IN (0, {$defaultStoreViewId}) ORDER BY `page_id`);
");

$installer->run("
ALTER TABLE `{$tablePageTree}`
    ADD `store_id` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT {$defaultStoreViewId},
    ADD `parent_id` SMALLINT( 6 ) NOT NULL DEFAULT 0,
    ADD `path` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
    ADD `position` SMALLINT( 6 ) UNSIGNED NOT NULL DEFAULT 0,
    ADD `level` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT 0,
    ADD `children_count` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT 0;

ALTER TABLE `{$tablePageTree}` CHANGE `identifier` `identifier` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ;

ALTER TABLE `{$tablePageTree}` ADD INDEX ( `store_id` ) ;

ALTER TABLE `{$tablePageTree}` ADD FOREIGN KEY ( `store_id` ) REFERENCES `{$tableCoreStore}` (
`store_id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE `{$tablePageTree}` ADD UNIQUE (
    `identifier` ,
    `store_id`
);
");

$home = Mage::getModel('cms/page')->load('home', 'identifier');
if (! $home->getId()) {
    $home = Clever_Cms_Model_Page::createDefaultStoreRootPage($defaultStoreViewId);
}
$homeId = $home->getId();

$installer->run("
UPDATE `{$tablePageTree}` SET
    `parent_id` = '{$homeId}',
    `path` = CONCAT('{$homeId}/', `page_id`),
    `position` = `page_id`,
    `level` = '2'
WHERE `page_id` != {$homeId};

UPDATE `{$tablePageTree}` SET
    `path` = '{$homeId}',
    `identifier` = '',
    `level` = '1',
    `position` = '1',
    `children_count` = (SELECT COUNT(*) - 1 FROM `{$tablePage}`)
WHERE `page_id` = {$homeId};
");

$installer->endSetup();
