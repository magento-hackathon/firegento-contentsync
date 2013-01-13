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

class FireGento_ContentSync_Model_Storage_File extends FireGento_ContentSync_Model_Storage_Abstract
{

    const DIRECTORY_CONFIG_PATH = 'contentsync/storage_file/directory';
    const FORMAT_CONFIG_PATH = 'contentsync/storage_file/format';

    /**
     * @var FireGento_ContentSync_Model_Storage_Format_Interface
     */
    protected $_format;

    /**
     * @return FireGento_ContentSync_Model_Storage_Format_Interface
     */
    public function getFormat()
    {
        if ( !$this->_format ) {
            $format = Mage::getStoreConfig(self::FORMAT_CONFIG_PATH);

            /** @var $instance FireGento_ContentSync_Model_Storage_Format_Interface */
            $instance = Mage::getModel('contentsync/storage_format_'.$format);

            if ( $instance )
            {
                $this->_format = $instance;
            } else {
                throw new Exception("output format $format not found");
            }

        }

        return $this->_format;
    }

    /**
     * Get directory to store files; create if necessary and test if it is writable.
     *
     * @return string
     * @throws Mage_Core_Exception
     */
    protected function _getStorageDirectory()
    {
        $directoryPath = Mage::getStoreConfig( self::DIRECTORY_CONFIG_PATH );

        if (!is_dir($directoryPath)) {
            if (!mkdir($directoryPath, 0777, true)) {
                Mage::throwException(
                    Mage::helper('contentsync')->__('Directory "%s" could not be created.', $directoryPath)
                );
            }
        }

        if (!is_dir_writeable($directoryPath)) {
            Mage::throwException(
                Mage::helper('contentsync')->__('Directory "%s" is not writable.', $directoryPath)
            );
        }

        if (!in_array(substr($directoryPath, -1 , 1), array('/', '\\'))) {
            $directoryPath .= DS;
        }

        return $directoryPath;
    }

    /**
     * @param array $data
     * @param string $entityType
     */
    public function storeData($data, $entityType) {

        $fileContent = $this->getFormat()->encode($data);
        $fileName = $this->_getEntityFilename( $entityType );


        if (file_put_contents($fileName, $fileContent) === false) {
            Mage::throwException(
                Mage::helper('contentsync')->__('File "%s" could not be written.', $fileName)
            );
        };
    }

    /**
     * @param $entityType
     * @return string
     */
    protected function _getEntityFilename( $entityType )
    {
        return $this->_getStorageDirectory() . $this->getFormat()->getFilename( $entityType );
    }

    /**
     * @param string $entityType
     * @return array
     */
    public function loadData($entityType)
    {
        $fileName = $this->_getEntityFilename($entityType);

        if (!is_file($fileName)) {
            return array();
        }

        if (($fileContents = file_get_contents($fileName)) === false) {
            return array();
        }

        return $this->getFormat()->decode($fileContents);
    }



}