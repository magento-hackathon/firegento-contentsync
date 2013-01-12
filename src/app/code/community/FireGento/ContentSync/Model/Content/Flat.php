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

class FireGento_ContentSync_Model_Content_Flat extends FireGento_ContentSync_Model_Content_Abstract {

    protected $_configPath = 'email_template';
    protected $_entityType = 'email_template';

    /**
     * @return array
     */
    public function _getEntityTypes()
    {
        return Mage::getStoreConfig('contentsync_entities');
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
        $entityTypes = $this->_getEntityTypes();

        $data = array();

        /* @var $collection Mage_Core_Model_Resource_Db_Collection_Abstract */
        $collection = Mage::getModel($entityTypes[$entityType]['model'])->getCollection();

        $collection->walk('afterLoad');

        foreach ($collection as $object) {

            /** @var $object Mage_Core_Model_Abstract */
            $objectData = $object->getData();
            foreach($objectData as $key => $value) {
                if (in_array($key, Mage::helper('contentsync/hash')->getFieldBlacklist())) {
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

    public function loadData()
    {
        Mage::getSingleton('contentsync/observer')->disableObservers();

        $importedEmailTemplateIds = array();

        /** @var $data array[] */
        $data = $this->loadDataFromStorage(
            $this->_entityType
        );

        foreach($data as $itemData) {

            $isNew = false;

            $importedEmailTemplateIds[] = $itemData['template_id'];

            /* @var $emailTemplate Mage_Core_Model_Email_Template */
            $emailTemplate = Mage::getModel('core/email_template')->load($itemData['template_id']);

            if (!$emailTemplate->getId()) {

                // new email: insert with new id which will be changed later
                $emailTemplateId = $itemData['template_id'];
                unset($itemData['template_id']);
                $isNew = true;
            }

            $emailTemplate
                ->setData($itemData)
                ->save();

            if ($isNew) {

                $this->_updateEmailTemplateId($emailTemplateId, $emailTemplate->getId());
            }
        }

        $this->_deleteEmailTemplatesNotImported($importedEmailTemplateIds);
    }

    /**
     * @param int[] $importedEmailTemplateIds
     */
    protected function _deleteEmailTemplatesNotImported($importedEmailTemplateIds)
    {
        $emailTemplatesToDelete = Mage::getResourceModel('core/email_template_collection')
            ->addFieldToFilter('template_id', array('nin' => $importedEmailTemplateIds));

        foreach ($emailTemplatesToDelete as $emailTemplate) {
            $emailTemplate->delete();
        }
    }

    /**
     * @param int $emailTemplateId
     * @param int $newEmailTemplateId
     */
    protected function _updateEmailTemplateId($emailTemplateId, $newEmailTemplateId)
    {
        if ($emailTemplateId == $newEmailTemplateId) {
            return;
        }

        /** @var $resource Mage_Core_Model_Resource */
        $resource = Mage::getSingleton('core/resource');

        /** @var $connection Varien_Db_Adapter_Pdo_Mysql */
        $connection = $resource->getConnection('core/write');

        $connection->update($resource->getTableName('core/email_template'), array('template_id' => $emailTemplateId), 'template_id = ' . $newEmailTemplateId);
    }
}