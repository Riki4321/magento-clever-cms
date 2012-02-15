<?php

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$tablePageTree = $installer->getTable('cms_page_tree');
$installer->run("
ALTER TABLE `{$tablePageTree}` ADD COLUMN `include_in_menu` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1';
");

$installer->endSetup();