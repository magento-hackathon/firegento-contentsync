<?php
/**
 * This file is part of a FireGento e.V. module.
 *
 * This FireGento e.V. module is free software; you can redistribute it and/or
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
 * @copyright 2013 FireGento Team (http://www.firegento.com)
 * @license   http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 */
/**
 * ContentSync Oberserver
 *
 * @category FireGento
 * @package  FireGento_ContentSync
 * @author   FireGento Team <team@firegento.com>
 */

class FireGento_ContentSync_Model_Observer
{
    protected $_helper = null;
    protected $_isDisabled = false;

    /**
     * @return array
     */
    public function _getEntityTypes()
    {
        return Mage::getStoreConfig('contentsync_entities');
    }

    /**
     * @return bool
     */
    protected function _isDisabled()
    {
        return $this->_isDisabled;
    }


    /**
     * @return FireGento_ContentSync_Helper_Data|null
     */
    protected function getHelper()
    {
        if ($this->_helper === null) {
            $this->_helper = Mage::helper('contentsync');
        }

        return $this->_helper;
    }

    /**
     * Observers won't be called after this method has been called
     */
    public function disableObservers()
    {
        $this->_isDisabled = true;
    }

    /**
     * Listens to:
     * - model_save_before
     *
     * @param  Varien_Event_Observer $observer
     * @return void
     */
    public function beforeObjectSave(Varien_Event_Observer $observer)
    {
        if ($this->_isDisabled()) {
            return;
        }

        $object = $observer->getEvent()->getObject();
        if ($object && $object instanceof Varien_Object && $this->_isObservedObjectType($object)) {
            $hash = Mage::helper('contentsync/hash')->calculateObjectHash($object);
            $object->setData('contentsync_hash', $hash);
        }
    }

    /**
     * Listens to:
     * - model_save_after
     *
     * @param  Varien_Event_Observer $observer
     * @return void
     */
    public function afterObjectSave(Varien_Event_Observer $observer)
    {
        if ($this->_isDisabled()) {
            return;
        }

        $object = $observer->getEvent()->getObject();
        if (
            $object &&
            $object instanceof Varien_Object &&
            $this->_isObservedObjectType($object) &&
            $object->hasDataChanges()
        ) {

            $entityTypeCode = $this->_getEntityTypeCodeByClass(get_class($object));
            if ($this->getHelper()->isTriggerAuto($entityTypeCode)) {
                Mage::getSingleton('contentsync/content_flat')->storeData($entityTypeCode);
            } elseif ($this->getHelper()->isTriggerManually($entityTypeCode)) {
                Mage::getSingleton('contentsync/notice')->setNoticeFlag($entityTypeCode);
            }
        }
    }

    /**
     * Listens to:
     * - model_delete_after
     *
     * @param  Varien_Event_Observer $observer
     * @return void
     */
    public function afterObjectDelete(Varien_Event_Observer $observer)
    {
        $object = $observer->getEvent()->getObject();
        if ($object && $object instanceof Varien_Object && $this->_isObservedObjectType($object)) {
            if ($this->_isDisabled()) {
                return;
            }

            $entityTypeCode = $this->_getEntityTypeCodeByClass(get_class($object));
            if ($this->getHelper()->isTriggerAuto($entityTypeCode)) {
                Mage::getSingleton('contentsync/content_flat')->storeData($entityTypeCode);
            } elseif ($this->getHelper()->isTriggerManually($entityTypeCode)) {
                Mage::getSingleton('contentsync/notice')->setNoticeFlag($entityTypeCode);
            }
        }
    }

    /**
     * @param  Varien_Object $object
     * @return bool
     */
    protected function _isObservedObjectType(Varien_Object $object)
    {
        foreach ($this->_getEntityTypes() as $entityTypeCode => $entityTypeData) {
            if ($object instanceof $entityTypeData['class']) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  string $className
     * @return string
     */
    protected function _getEntityTypeCodeByClass($className)
    {
        foreach ($this->_getEntityTypes() as $entityTypeCode => $entityTypeData) {
            if ($className == $entityTypeData['class'] || is_subclass_of($className, $entityTypeData['class'])) {
                return $entityTypeCode;
            }
        }

        return '';
    }
}
