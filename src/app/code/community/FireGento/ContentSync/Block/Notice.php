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
 * This class generates noticeses
 *
 * @category FireGento
 * @package  FireGento_ContentSync
 * @author   FireGento Team <team@firegento.com>
 */

class FireGento_ContentSync_Block_Notice extends Mage_Adminhtml_Block_Abstract
{
    /**
     * @return array
     */
    public function getNotices()
    {
        return Mage::getSingleton('contentsync/notice')->getNoticeFlag();
    }

    /**
     * @param  string      $code
     * @return null|string
     */
    public function getLabel($code)
    {
        return Mage::getSingleton('contentsync/notice')->getManualUpdateNoticeTypeLabel($code);
    }

    /**
     * @param  string      $code
     * @return null|string
     */
    public function getExportUrl($code)
    {
        return Mage::getSingleton('contentsync/notice')->getExportUrl($code);
    }

    /**
     * @return null|string Update action URL
     */
    public function getManageUrl()
    {
        return Mage::helper('adminhtml')->getUrl('adminhtml/contentsync/manage');
    }

    /**
     * @param  string $code
     * @return string
     */
    public function getIgnoreUrl($code)
    {
        $backUrl = Mage::helper('core/url')->getCurrentBase64Url();
        return Mage::helper('adminhtml')->getUrl('adminhtml/contentsync/close',
            array('content' => $code,
                  'back' => $backUrl));
    }


}
