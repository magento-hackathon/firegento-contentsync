<?php

/**
 * This file is part of the FIREGENTO project.
 * 
 * FireGento_ContentSync is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 3 as
 * published by the Free Software Foundation.
 * 
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * 
 * PHP version 5
 * 
 * @category  FireGento
 * @package   FireGento_ContentSync
 * @author    FireGento Team <team@firegento.com>
 * @copyright 2013 FireGento Team (http://www.firegento.de). All rights served.
 * @license   http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version   $Id:$
 * @since     0.1.0
 */
/* @var $this Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
        ->newTable($installer->getTable('contentsync/hash'))
        ->addColumn('hash_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'identity' => true,
            'nullable' => false,
            'primary' => true,
                ), 'Hash ID')
        ->addColumn('entity_type', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
            'nullable' => false,
                ), 'Entity Type')
        ->addColumn('entity_hash', Varien_Db_Ddl_Table::TYPE_TEXT, 40, array(
            'nullable' => false,
                ), 'Aggregate hash of the entity')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(), 'Hash calculation time')
        ->addIndex($installer->getIdxName('contentsync/hash', array('entity_type')), array('entity_type'), array(
            'type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ))
        ->setComment('Aggregate hashes of the single entities');
$installer->getConnection()->createTable($table);

$installer->endSetup();
