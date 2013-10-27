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
 * Block Class
 *
 * @category FireGento
 * @package  FireGento_ContentSync
 * @author   FireGento Team <team@firegento.com>
 */

class FireGento_ContentSync_Model_Content_Cms_Block extends FireGento_ContentSync_Model_Content_Abstract
{
    protected $_configPath = 'cms_block';

    protected $_entityType = 'cms_block';

    public function storeData()
    {
        $data = array();

        /* @var $cmsBlocks Mage_Cms_Model_Resource_Block_Collection */
        $cmsBlocks = Mage::getResourceModel('cms/block_collection');

        foreach ($cmsBlocks as $cmsBlock) {

            /** @var cmsBlock Mage_Cms_Model_Block */
            $blockData = $cmsBlock->getData();
            unset($blockData['creation_time']);
            unset($blockData['update_time']);
            $data[] = $blockData;
        }

        $this->storeDataInStorage(
            $data,
            $this->_entityType
        );
    }

    public function loadData()
    {
        Mage::getSingleton('contentsync/observer')->disableObservers();

        $importedBlockIds = array();

        /** @var $data array[] */
        $data = $this->loadDataFromStorage(
            $this->_entityType
        );

        foreach ($data as $itemData) {

            $isNew = false;

            $importedBlockIds[] = $itemData['block_id'];

            /* @var $cmsBlock Mage_Cms_Model_Block */
            $cmsBlock = Mage::getModel('cms/block')->load($itemData['block_id']);

            if (!$cmsBlock->getId()) {

                // new block: insert with new id which will be changed later
                $blockId = $itemData['block_id'];
                unset($itemData['block_id']);
                $isNew = true;
            }

            $cmsBlock
                ->setData($itemData)
                ->save();

            if ($isNew) {

                $this->_updateBlockId($blockId, $cmsBlock->getId());
            }
        }

        $this->_deleteBlocksNotImported($importedBlockIds);
    }

    /**
     * @param int[] $importedBlockIds
     */
    protected function _deleteBlocksNotImported($importedBlockIds)
    {
        $cmsBlocksToDelete = Mage::getResourceModel('cms/block_collection')
            ->addFieldToFilter('block_id', array('nin' => $importedBlockIds));

        foreach ($cmsBlocksToDelete as $block) {
            $block->delete();
        }
    }

    /**
     * @param int $blockId
     * @param int $newBlockId
     */
    protected function _updateBlockId($blockId, $newBlockId)
    {
        if ($blockId == $newBlockId) {
            return;
        }

        /** @var $resource Mage_Core_Model_Resource */
        $resource = Mage::getSingleton('core/resource');

        /** @var $connection Varien_Db_Adapter_Pdo_Mysql */
        $connection = $resource->getConnection('core/write');

        $connection->update($resource->getTableName('cms/block'), array('block_id' => $blockId),
            'block_id = ' . $newBlockId);
    }
}
