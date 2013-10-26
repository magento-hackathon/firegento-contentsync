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

abstract class FireGento_ContentSync_Model_Content_Abstract
{
    protected $_createdItems = array();
    protected $_updatedtems = array();
    protected $_deletedtems = array();

    public function getCreatedItems()
    {
        return $this->_createdItems;
    }

    public function getUpdatedItems()
    {
        return $this->_updatedtems;
    }

    public function getDeletedItems()
    {
        return $this->_deletedtems;
    }

    public function getOverview($linebreak = "\n")
    {
        $output = '';

        if (sizeof($this->getCreatedItems())) {
            $output .= Mage::helper('contentsync')->__('Created Items') . ': ';
            $output .= $linebreak;
            foreach ($this->getCreatedItems() as $entityType => $items) {
                $output .= '   ' . sizeof($items) . ' ' . Mage::helper('contentsync')->__(Mage::getStoreConfig('contentsync_entities/' . $entityType . '/label'));
                $output .= $linebreak;
            }
        }

        if (sizeof($this->getUpdatedItems())) {
            $output .= Mage::helper('contentsync')->__('Updated Items') . ': ';
            $output .= $linebreak;
            foreach ($this->getUpdatedItems() as $entityType => $items) {
                $output .= '   ' . sizeof($items) . ' ' . Mage::helper('contentsync')->__(Mage::getStoreConfig('contentsync_entities/' . $entityType . '/label'));
                $output .= $linebreak;
            }
        }

        if (sizeof($this->getDeletedItems())) {
            $output .= Mage::helper('contentsync')->__('Deleted Items') . ': ';
            $output .= $linebreak;
            foreach ($this->getDeletedItems() as $entityType => $items) {
                $output .= '   ' . sizeof($items) . ' ' . Mage::helper('contentsync')->__(Mage::getStoreConfig('contentsync_entities/' . $entityType . '/label'));
                $output .= $linebreak;
            }
        }

        return $output;
    }

    /**
     * @param  string                                       $entityType
     * @return FireGento_ContentSync_Model_Storage_Abstract
     */
    public function getStorage($entityType)
    {
        $storageName = Mage::getStoreConfig('contentsync/content_'.$entityType.'/storage');
        if (!$storageName) {
            return Mage::getModel('contentsync/storage_void');
        }
        $storage = Mage::getModel('contentsync/storage_'.$storageName);
        if (!$storage) {
            return Mage::getModel('contentsync/storage_void');
        }
        return $storage;
    }

    /**
     * @param array  $data
     * @param string $entityType
     */
    protected function storeDataInStorage($data, $entityType)
    {
        $this->getStorage($entityType)->storeData($data, $entityType);
    }

    /**
     * @param  string $entityType
     * @return array
     */
    protected function loadDataFromStorage($entityType)
    {
        return $this->getStorage($entityType)->loadData($entityType);
    }
}
