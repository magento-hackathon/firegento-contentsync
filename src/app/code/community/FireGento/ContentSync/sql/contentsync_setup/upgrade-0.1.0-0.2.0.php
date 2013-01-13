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

$dataTypes = array(
    'cms/block',
    'cms/page',
    'core/email_template',
);

foreach ($dataTypes as $dataType) {
    $tableName = Mage::getSingleton('core/resource')->getTableName($dataType);
    $installer->getConnection()->addColumn($tableName, 'contentsync_hash', 'char(40)');
}

$installer->endSetup();
