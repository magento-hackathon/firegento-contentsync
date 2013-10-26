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
     * @param array  $data
     * @param string $entityType
     */
    public function storeData($data, $entityType)
    {
        $fileContent = $this->_prettyPrint(Zend_Json::encode($data));
        $fileName = $this->_getEntityFilename( $entityType );


        if (file_put_contents($fileName, $fileContent) === false) {
            Mage::throwException(
                Mage::helper('contentsync')->__('File "%s" could not be written.', $fileName)
            );
        };

        // write hash file
        $entityTypeModelName = Mage::getStoreConfig('contentsync_entities/' . $entityType . '/model');
        $tableHash = Mage::helper('contentsync/hash')->calculateTableHash($entityTypeModelName);
        $fileName = $this->_getEntityHashFilename( $entityType );

        if (file_put_contents($fileName, $tableHash) === false) {
            Mage::throwException(
                Mage::helper('contentsync')->__('File "%s" could not be written.', $fileName)
            );
        };

        /* @var $hash FireGento_ContentSync_Model_Hash */
        $hash = Mage::getModel('contentsync/hash')->loadByEntityType($entityType);

        $hash
            ->setEntityType($entityType)
            ->setEntityHash($tableHash)
            ->save();
    }

    /**
     * @param $entityType
     * @return string
     */
    protected function _getEntityFilename( $entityType )
    {
        return $this->_getStorageDirectory() . $entityType . '.json';
    }

    /**
     * @param $entityType
     * @return string
     */
    protected function _getEntityHashFilename( $entityType )
    {
        return $this->_getStorageDirectory() . $entityType . '.hash';
    }

    /**
     * @param  string $entityType
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

        $hashFileName = $this->_getEntityHashFilename($entityType);
        $entityHashFromFile = file_get_contents($hashFileName);

        /* @var $hashEntity FireGento_ContentSync_Model_Hash */
        $hashEntity = Mage::getModel('contentsync/hash')->loadByEntityType($entityType);
        $entityHashFromDatabase = $hashEntity->getEntityHash();

        if ($entityHashFromDatabase && $entityHashFromDatabase == $entityHashFromFile) {
            echo 'Import of ' . $entityType . ' skipped.' . "\n";
            return array();
        }

        $hashEntity
            ->setEntityType($entityType)
            ->setEntityHash($entityHashFromFile)
            ->save();

        return Zend_Json::decode($fileContents);
    }


    /**
     * @param  string $json
     * @return string
     */
    protected function _prettyPrint($json)
    {
        $result = '';
        $level = 0;
        $prev_char = '';
        $in_quotes = false;
        $ends_line_level = NULL;
        $json_length = strlen( $json );

        for ($i = 0; $i < $json_length; $i++) {
            $char = $json[$i];
            $new_line_level = NULL;
            $post = "";
            if ($ends_line_level !== NULL) {
                $new_line_level = $ends_line_level;
                $ends_line_level = NULL;
            }
            if ($char === '"' && $prev_char != '\\') {
                $in_quotes = !$in_quotes;
            } elseif (! $in_quotes) {
                switch ($char) {
                    case '}': case ']':
                    $level--;
                    $ends_line_level = NULL;
                    $new_line_level = $level;
                    break;

                    case '{': case '[':
                    $level++;
                    case ',':
                        $ends_line_level = $level;
                        break;

                    case ':':
                        $post = " ";
                        break;

                    case " ": case "\t": case "\n": case "\r":
                    $char = "";
                    $ends_line_level = $new_line_level;
                    $new_line_level = NULL;
                    break;
                }
            }
            if ($new_line_level !== NULL) {
                $result .= "\n".str_repeat( "\t", $new_line_level );
            }
            $result .= $char.$post;
            $prev_char = $char;
        }

        return $result;
    }
}
