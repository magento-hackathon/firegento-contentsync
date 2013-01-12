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

class FireGento_ContentSync_Model_Observer
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
     * @return FireGento_ContentSync_Helper_Data|null
     */
    protected function getHelper()
    {
        if ($this->_helper === null) {
            $this->_helper = Mage::helper('contentsync');
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
            Mage::getSingleton('contentsync/content_cms_page')->storeData();
        } elseif ($this->getHelper()->getCmsPageTriggerManually()) {
            Mage::getSingleton('contentsync/notice')->showManuelCmsPageUpdateNotice();
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
            Mage::getSingleton('contentsync/content_cms_block')->storeData();
        } elseif ($this->getHelper()->getCmsBlockTriggerManually()) {
            Mage::getSingleton('contentsync/notice')->showManuelCmsBlockUpdateNotice();
        }
    }

    /**
     * Listens to:
     * - model_save_before
     * 
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function beforeObjectSave(Varien_Event_Observer $observer)
    {
        $object = $observer->getEvent()->getObject();
        if ($object && $object instanceof Varien_Object && $this->_isObservedObjectType($object)) {
            $hash = Mage::helper('contentsync/hash')->calculateObjectHash($object);
            $object->setData('contentsync_hash', $hash);
        }
    }
    
    protected function _isObservedObjectType(Varien_Object $object)
    {
        $objectTypes = array(
            'Mage_Cms_Model_Page',
            'Mage_Cms_Model_Block',
            'Mage_Core_Model_Email_Template',
        );

        foreach ($objectTypes as $type) {
            if ($object instanceof $type) {
                return true;
            }
        }

        return false;
    }
}
