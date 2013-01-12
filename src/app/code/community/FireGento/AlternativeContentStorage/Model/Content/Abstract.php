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

abstract class FireGento_AlternativeContentStorage_Model_Content_Abstract
{

    protected $_config_path = '';

    protected function getConfig( $key )
    {
        return Mage::getStoreConfig('acs/content_'.$this->_config_path.'/'.$key);
    }

    /**
     * @return FireGento_AlternativeContentStorage_Model_Storage_Abstract[]
     */
    public function getStorages()
    {
        $result = array();
        $storages = explode(',', $this->getConfig('storages') );
        foreach(  $storages AS $storage_name )
        {
            $storage = Mage::getModel('acs/storage_'.$storage_name);
            if ( $storage ) {
                $result[] = $storage;
            }
        }
        return $result;
    }

    /**
     * @param string $data
     * @param string $entityType
     */
    protected function storeDataInStorages($data, $entityType)
    {
        foreach($this->getStorages() as $storage) {
            $storage->storeData($data, $entityType);
        }
    }
}