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
 * @package   FireGento_AlternativeContentStorage
 * @author    FireGento Team <team@firegento.com>
 * @copyright 2013 FireGento Team (http://www.firegento.de). All rights served.
 * @license   http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version   $Id:$
 * @since     0.1.0
 */

class FireGento_AlternativeContentStorage_Model_Storage_File extends FireGento_AlternativeContentStorage_Model_Storage_Abstract
{
    /**
     * Get directory to store files; create if necessary and test if it is writable.
     *
     * @return string
     * @throws Mage_Core_Exception
     */
    protected function _getStorageDirectory()
    {
        $directoryPath = Mage::getStoreConfig('acs/storage_file/directory');

        if (!is_dir($directoryPath)) {
            if (!mkdir($directoryPath, 0777, true)) {
                Mage::throwException(
                    Mage::helper('acs')->__('Directory "%s" could not be created.')
                );
            }
        }

        if (!is_dir_writeable($directoryPath)) {
            Mage::throwException(
                Mage::helper('acs')->__('Directory "%s" is not writable.')
            );
        }

        return $directoryPath;
    }
}