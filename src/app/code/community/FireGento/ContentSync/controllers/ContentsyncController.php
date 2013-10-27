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
 * Data Export Class
 *
 * @category FireGento
 * @package  FireGento_ContentSync
 * @author   FireGento Team <team@firegento.com>
 */

class FireGento_ContentSync_ContentsyncController extends Mage_Adminhtml_Controller_Action
{


    public function exportAction()
    {
        $entityType = $this->getRequest()->getParam('content');

        try {
            Mage::getSingleton('contentsync/content_flat')->storeData($entityType);
            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__('%s have been exported successfully.',
                    Mage::getStoreConfig('contentsync_entities/' . $entityType . '/label'))
            );
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError(
                $this->__('Error while exporting %s: %s',
                    Mage::getStoreConfig('contentsync_entities/' . $entityType . '/label'), $e->getMessage())
            );
            Mage::logException($e);
        }
        $this->_forward('close');
    }


    public function closeAction()
    {
        $type = $this->getRequest()->getParam('content');

        Mage::getSingleton('contentsync/notice')->unsetNoticeFlag($type);

        $this->_goBack();
    }


    protected function _goBack()
    {
        $backUrl = $this->getRequest()->getParam('back');
        $backUrl = Mage::helper('core/url')->urlDecode($backUrl);

        $this->_redirectUrl($backUrl);
    }


}
