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

class FireGento_AlternativeContentStorage_Model_Content_Cms_Block extends FireGento_AlternativeContentStorage_Model_Content_Cms_Abstract {

    protected $_configPath = 'cms_block';

    protected $_entityType = 'cms_block';

    public function storeData()
    {
        $data = array();

        /* @var $cmsBlocks Mage_Cms_Model_Resource_Block_Collection */
        $cmsBlocks = Mage::getResourceModel('cms/block_collection');

        foreach($cmsBlocks as $cmsBlock) {

            /** @var $cmsBlock Mage_Cms_Model_Block */
            $data[] = $cmsBlock->getData();
        }

        $this->storeDataInStorage(
            $data,
            $this->_entityType
        );
    }

    public function loadData()
    {
        /** @var $data array[] */
        $data = $this->loadDataFromStorage(
            $this->_entityType
        );

        foreach($data as $itemData) {
            /* @var $cmsBlock Mage_Cms_Model_Block */
            $cmsBlock = Mage::getModel('cms/block')->load($itemData['block_id']);

            $cmsBlock
                ->addData($itemData)
                ->save();
        }
    }
}