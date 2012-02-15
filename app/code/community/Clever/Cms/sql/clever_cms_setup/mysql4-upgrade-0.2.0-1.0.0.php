<?php

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$tablePageTree = $installer->getTable('cms_page_tree');
$installer->run("
ALTER TABLE `{$tablePageTree}` CHANGE `position` `position` SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0';
");

$installer->endSetup();