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

    /**
     * Listens to:
     * - model_save_after
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function afterObjectSave(Varien_Event_Observer $observer)
    {
        $object = $observer->getEvent()->getObject();
        if ($object && $object instanceof Varien_Object && $this->_isObservedObjectType($object)) {
            if (!$object->hasDataChanges() || $this->_isDisabled()) {
                return;
            }

            $code = $this->_getCodeByClass(get_class($object));
            if ($this->getHelper()->isTriggerAuto($code)) {
                Mage::getSingleton('contentsync/content_flat')->storeData($code);
            } elseif ($this->getHelper()->isTriggerManually($code)) {
                Mage::getSingleton('contentsync/notice')->setNoticeFlag($code);
            }
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

    protected function _getCodeByClass($className)
    {
        switch($className) {

            case 'Mage_Cms_Model_Page':
                return 'cms_page';

            case 'Mage_Cms_Model_Block':
                return 'cms_block';

            case 'Mage_Core_Model_Email_Template':
                return 'email_template';

            default:
                return '';
        }
    }
}
