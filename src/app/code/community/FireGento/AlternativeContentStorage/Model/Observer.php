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

class FireGento_AlternativeContentStorage_Model_Observer
{


    protected $_helper = null;
    protected $_isDisabled = false;


    /**
     * @return bool
     */
    protected function _isDisabled()
    {
        return $this->_isDisabled;
    }


    /**
     * @return FireGento_AlternativeContentStorage_Helper_Data|null
     */
    protected function getHelper()
    {
        if ($this->_helper === null) {
            $this->_helper = Mage::helper('acs');
        }

        return $this->_helper;
    }


    public function disableObservers()
    {
        $this->_isDisabled = true;
    }


    /**
     * @param Varien_Event_Observer $observer
     */
    public function afterCmsPageSave(Varien_Event_Observer $observer)
    {
        /** @var $cmsPage Mage_Cms_Model_Page */
        $cmsPage = $observer->getObject();

        if (!$cmsPage->hasDataChanges() || $this->_isDisabled()) {
            return;
        }

        if ($this->getHelper()->getCmsPageTriggerAuto()) {
            //Mage::getSingleton('acs/content_cms_page')->storeData();
        } elseif ($this->getHelper()->getCmsPageTriggerManually()) {
            FireGento_AlternativeContentStorage_Model_Notice::showManuelCmsPageUpdateNotice();
            FireGento_AlternativeContentStorage_Model_Notice::showManuelCmsBlockUpdateNotice();
        }
    }


    /**
     * @param Varien_Event_Observer $observer
     */
    public function afterCmsBlockSave(Varien_Event_Observer $observer)
    {
        /** @var $cmsBlock Mage_Cms_Model_Block */
        $cmsBlock = $observer->getObject();

        if (!$cmsBlock->hasDataChanges() || $this->_isDisabled()) {
            return;
        }

        if ($this->getHelper()->getCmsBlockTriggerAuto()) {
            Mage::getSingleton('acs/content_cms_block')->storeData();
        } elseif ($this->getHelper()->getCmsBlockTriggerManually()) {
            FireGento_AlternativeContentStorage_Model_Notice::showManuelCmsBlockUpdateNotice();
        }
    }
}