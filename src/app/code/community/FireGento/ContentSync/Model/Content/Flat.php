<?php
/**
 * This file is part of the FIREGENTO project.
 *
 * FireGento_GermanSetup is free software; you can redistribute it and/or
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

class FireGento_ContentSync_Model_Content_Flat extends FireGento_ContentSync_Model_Content_Abstract
{
    /**
     * @return array
     */
    public function _getEntityTypes()
    {
        return Mage::getStoreConfig('contentsync_entities');
    }


    /**
     * @param string $entityType
     * @return FireGento_ContentSync_Model_Storage_Abstract
     */
    public function getStorage($entityType)
    {
        $model = $this->_getEntityTypeModel($entityType);

        $storage = parent::getStorage($entityType);
        $storage->setIdField( $model->getIdFieldName() );
        return $storage;
    }

    /**
     * @param string $entityType
     * @return string
     */
    protected function _getEntityTypeModelName($entityType)
    {
        $entityTypeData = $this->_getEntityTypes();

        $modelName = $entityTypeData[$entityType]['model'];
        return $modelName;
    }

    /**
     * @param string $entityType
     * @return string
     */
    protected function _getEntityTypeTableName($entityType)
    {
        $entityTypeData = $this->_getEntityTypes();

        $tableName = $entityTypeData[$entityType]['table_name'];
        return $tableName;
    }

    /**
     * @param string $entityType
     * @return bool
     */
    protected function _canDeleteEntities($entityType)
    {
        $entityTypeData = $this->_getEntityTypes();

        return (bool)$entityTypeData[$entityType]['allow_delete'];
    }

    /**
     * @param string $entityType
     * @return Mage_Core_Model_Abstract
     */
    protected function _getEntityTypeModel($entityType)
    {
        return Mage::getModel($this->_getEntityTypeModelName($entityType));
    }

    /**
     * @param $entityType
     * @return string
     */
    protected function _getEntityTypePrimaryKey($entityType)
    {
        $mainKey = $this->_getEntityTypeModel($entityType)
            ->getResource()
            ->getIdFieldName();

        return $mainKey;
    }

    public function storeData($entityType = null)
    {
        if (!is_null($entityType)) {

            $this->storeDataForEntityType($entityType);
        } else {

            foreach($this->_getEntityTypes() as $entityType => $entityTypeData) {

                $this->storeDataForEntityType($entityType);
            }
        }
    }

    public function storeDataForEntityType($entityType)
    {
        Mage::getSingleton('contentsync/observer')->disableObservers();

        $data = array();

        /* @var $collection Mage_Core_Model_Resource_Db_Collection_Abstract */
        $collection = Mage::getModel($this->_getEntityTypeModelName($entityType))->getCollection();

        $collection->walk('afterLoad');

        foreach ($collection as $object) {

            if (!$object->getData('contentsync_hash')) {
                $hash = Mage::helper('contentsync/hash')->calculateObjectHash($object);
                $object
                    ->setData('contentsync_hash', $hash)
                    ->save();
            }

            /** @var $object Mage_Core_Model_Abstract */
            $objectData = $object->getData();
            foreach($objectData as $key => $value) {
                if (
                    $key != 'contentsync_hash'
                    && in_array($key, Mage::helper('contentsync/hash')->getFieldBlacklist())
                ) {
                    unset($objectData[$key]);
                }
            }
            $data[] = $objectData;
        }

        $this->storeDataInStorage(
            $data,
            $entityType
        );
    }

    public function loadData($entityType = null)
    {
        Mage::getSingleton('contentsync/observer')->disableObservers();

        if (!is_null($entityType)) {

            $this->loadDataForEntityType($entityType);
        } else {

            foreach($this->_getEntityTypes() as $entityType => $entityTypeData) {

                $this->loadDataForEntityType($entityType);
            }
        }
    }

    public function loadDataForEntityType($entityType)
    {
        $modelName = $this->_getEntityTypeModelName($entityType);

        $mainKey = $this->_getEntityTypePrimaryKey($entityType);

        $importedObjectIds = array();

        /** @var $data array[] */
        $data = $this->loadDataFromStorage(
            $entityType
        );

        foreach ($data as $itemData) {

            $isNew = false;

            $importedObjectIds[] = $itemData[$mainKey];

            /* @var $object Mage_Core_Model_Email_Template */
            $object = Mage::getModel($modelName)->load($itemData[$mainKey]);

            if (!$object->getId()) {

                // new object: insert with new id which will be changed later
                $objectId = $itemData[$mainKey];
                unset($itemData[$mainKey]);
                $isNew = true;
            }

            $object
                ->setData($itemData)
                ->save();

            if ($isNew) {

                $this->_updateObjectId($entityType, $objectId, $object->getId());
            }
        }

        if ($this->_canDeleteEntities($entityType)) {

            $this->_deleteObjectsNotImported($entityType, $importedObjectIds);
        }
    }

    /**
     * @param string $entityType
     * @param int[] $importedObjectIds
     */
    protected function _deleteObjectsNotImported($entityType, $importedObjectIds)
    {
        $primaryKey = $this->_getEntityTypePrimaryKey($entityType);

        $objectsToDelete = $this->_getEntityTypeModel($entityType)->getCollection()
            ->addFieldToFilter($primaryKey, array('nin' => $importedObjectIds));

        foreach ($objectsToDelete as $object) {
            $object->delete();
        }
    }

    /**
     * @param string $entityType
     * @param int $objectId
     * @param int $newObjectId
     */
    protected function _updateObjectId($entityType, $objectId, $newObjectId)
    {
        if ($objectId == $newObjectId) {
            return;
        }

        $primaryKey = $this->_getEntityTypePrimaryKey($entityType);

        /** @var $resource Mage_Core_Model_Resource */
        $resource = Mage::getSingleton('core/resource');

        /** @var $connection Varien_Db_Adapter_Pdo_Mysql */
        $connection = $resource->getConnection('core/write');

        $connection->update(
            $resource->getTableName($this->_getEntityTypeTableName($entityType)),
            array($primaryKey => $objectId),
            $primaryKey . ' = ' . $newObjectId);
    }
}