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

class FireGento_AlternativeContentStorage_Model_Content_Cms_Page extends FireGento_AlternativeContentStorage_Model_Content_Cms_Abstract
{
    protected $_configPath = 'cms_page';

    protected $_entityType = 'cms_page';

    public function storeData()
    {
        $data = array();

        /* @var $cmsPages Mage_Cms_Model_Resource_Page_Collection */
        $cmsPages = Mage::getResourceModel('cms/page_collection');

        foreach($cmsPages as $cmsPage) {

            /** @var cmsPage Mage_Cms_Model_Page */
            $data[] = $cmsPage->getData();
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
            /* @var $cmsPage Mage_Cms_Model_Page */
            $cmsPage = Mage::getModel('cms/page')->load($itemData['page_id']);

            $cmsPage
                ->addData($itemData)
                ->save();
        }
    }
}