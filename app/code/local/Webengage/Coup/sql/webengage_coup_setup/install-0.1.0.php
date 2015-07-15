<?php

$installer = $this;
$installer->startSetup();



/**
 * Create Registry Type Table
 *
 *
 */
$tableName = $installer->getTable('webengage_coup/itemtrack');
// Check if the table already exists
if ($installer->getConnection()->isTableExists($tableName) != true) {
    $table = $installer->getConnection()
        ->newTable($installer->getTable('webengage_coup/itemtrack'))
        ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity'  => true,
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ), 'Type Id')
        ->addColumn('url', Varien_Db_Ddl_Table::TYPE_TEXT, 250, array(
            'nullable'  => true,
        ), 'Url')
        ->addColumn('itemid', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable'  => true,
        ), 'ItemId')

        ->setComment('Webengage ItemTrack tables');
    $installer->getConnection()->createTable($table);
}

$installer->run("
		DROP TABLE IF EXISTS {$this->getTable('webengage_coup/settings')};
		CREATE TABLE IF NOT EXISTS {$this->getTable('webengage_coup/settings')} (
		`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		`option_key` varchar(64) NOT NULL default '',
		`option_value` text NOT NULL default '',
		PRIMARY KEY (`id`)
		)  ENGINE=InnoDB DEFAULT CHARSET=utf8;
	");

	$installer->run("INSERT INTO {$this->getTable('webengage_coup/settings')} (option_key, option_value) values ('license_code', '');");
	$installer->run("INSERT INTO {$this->getTable('webengage_coup/settings')} (option_key, option_value) values ('widget_status', '');");



$installer->endSetup();
