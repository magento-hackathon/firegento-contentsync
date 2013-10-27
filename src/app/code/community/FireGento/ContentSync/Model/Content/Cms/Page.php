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
 * Page Class
 *
 * @category FireGento
 * @package  FireGento_ContentSync
 * @author   FireGento Team <team@firegento.com>
 */

class FireGento_ContentSync_Model_Content_Cms_Page extends FireGento_ContentSync_Model_Content_Abstract
{
    protected $_configPath = 'cms_page';

    protected $_entityType = 'cms_page';

    public function storeData()
    {
        $data = array();

        /* @var $cmsPages Mage_Cms_Model_Resource_Page_Collection */
        $cmsPages = Mage::getResourceModel('cms/page_collection');

        foreach ($cmsPages as $cmsPage) {

            /** @var $cmsPage Mage_Cms_Model_Page */
            $pageData = $cmsPage->getData();
            unset($pageData['creation_time']);
            unset($pageData['update_time']);
            $data[] = $pageData;
        }

        $this->storeDataInStorage(
            $data,
            $this->_entityType
        );
    }

    public function loadData()
    {
        Mage::getSingleton('contentsync/observer')->disableObservers();

        $importedPageIds = array();

        /** @var $data array[] */
        $data = $this->loadDataFromStorage(
            $this->_entityType
        );

        foreach ($data as $itemData) {

            $isNew = false;

            $importedPageIds[] = $itemData['page_id'];

            /* @var $cmsPage Mage_Cms_Model_Page */
            $cmsPage = Mage::getModel('cms/page')->load($itemData['page_id']);

            if (!$cmsPage->getId()) {

                // new page: insert with new id which will be changed later
                $pageId = $itemData['page_id'];
                unset($itemData['page_id']);
                $isNew = true;
            }

            $cmsPage
                ->setData($itemData)
                ->save();

            if ($isNew) {

                $this->_updatePageId($pageId, $cmsPage->getId());
            }
        }

        $this->_deletePagesNotImported($importedPageIds);
    }

    /**
     * @param int[] $importedPageIds
     */
    protected function _deletePagesNotImported($importedPageIds)
    {
        $cmsPagesToDelete = Mage::getResourceModel('cms/page_collection')
            ->addFieldToFilter('page_id', array('nin' => $importedPageIds));

        foreach ($cmsPagesToDelete as $page) {
            $page->delete();
        }
    }

    /**
     * @param int $pageId
     * @param int $newPageId
     */
    protected function _updatePageId($pageId, $newPageId)
    {
        if ($pageId == $newPageId) {
            return;
        }

        /** @var $resource Mage_Core_Model_Resource */
        $resource = Mage::getSingleton('core/resource');

        /** @var $connection Varien_Db_Adapter_Pdo_Mysql */
        $connection = $resource->getConnection('core/write');

        $connection->update($resource->getTableName('cms/page'), array('page_id' => $pageId),
            'page_id = ' . $newPageId);
    }
}
