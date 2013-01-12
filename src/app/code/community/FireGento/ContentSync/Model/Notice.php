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

class FireGento_ContentSync_Model_Notice
{


    const NOTICE_REGISTER_KEY = 'notice_register_key';

    const NOTICE_TYPE_CMS_BLOCK = 'cms_block';
    const NOTICE_TYPE_CMS_PAGE = 'cms_page';
    const NOTICE_TYPE_EMAIL_TRANS = 'email_trans';
    const NOTICE_TYPE_CORE_CONFIG = 'core_config';

    /**
     * @var Mage_Core_Model_Flag
     */
    protected $_flagModel = null;

    /**
     * @var array
     */
    protected $_flagData = null;


    /**
     *
     */
    public function __construct()
    {
        $this->_flagModel = Mage::getModel('core/flag', array('flag_code' => self::NOTICE_REGISTER_KEY));
    }


    /**
     * @return Mage_Core_Model_Flag
     */
    protected function _getFlagModel()
    {
        return $this->_flagModel;
    }


    /**
     * @return Mage_Core_Model_Flag
     */
    protected function _loadFlagData()
    {
        if ($this->_flagData === null) {
            $this->_getFlagModel()->loadSelf();
            $this->_flagData = $this->_getFlagModel()->getFlagData();
            $this->_flagData = @unserialize($this->_flagData);
            $this->_flagData = is_array($this->_flagData) ? $this->_flagData : array();
        }
    }


    /**
     *
     */
    protected function _saveFlagData()
    {
        $data = is_array($this->_flagData) ? $this->_flagData : array();
        $data = @serialize($data);
        $this->_getFlagModel()->setFlagData($data);
        $this->_getFlagModel()->save();
    }


    /**
     * @return array()
     */
    public function getNoticeFlag()
    {
        $this->_loadFlagData();

        return $this->_flagData;
    }


    /**
     * @param string $type
     * @return FireGento_ContentSync_Model_Notice
     */
    public function setNoticeFlag($type)
    {
        $this->_loadFlagData();
        $this->_flagData[$type] = $type;
        $this->_saveFlagData();

        return $this;
    }


    /**
     * @param string $type
     * @return bool
     */
    public function hasNoticeFlag($type)
    {
        $this->_loadFlagData();

        return array_key_exists($type, $this->_flagData);
    }


    /**
     * @param string $type
     * @return FireGento_ContentSync_Model_Notice
     */
    public function unsetNoticeFlag($type)
    {
        $this->_loadFlagData();
        unset($this->_flagData[$type]);
        $this->_saveFlagData();

        return $this;
    }


    /**
     * @return FireGento_ContentSync_Model_Notice
     */
    public function showManuelCmsBlockUpdateNotice()
    {
        $this->setNoticeFlag(self::NOTICE_TYPE_CMS_BLOCK);
        return $this;
    }


    /**
     * @return FireGento_ContentSync_Model_Notice
     */
    public function showManuelCmsPageUpdateNotice()
    {
        $this->setNoticeFlag(self::NOTICE_TYPE_CMS_PAGE);
        return $this;
    }


    /**
     * @return FireGento_ContentSync_Model_Notice
     */
    public function showManuelEmailTransUpdateNotice()
    {
        $this->setNoticeFlag(self::NOTICE_TYPE_EMAIL_TRANS);
        return $this;
    }


    /**
     * @return FireGento_ContentSync_Model_Notice
     */
    public function showManuelCoreConfigUpdateNotice()
    {
        $this->setNoticeFlag(self::NOTICE_TYPE_CORE_CONFIG);
        return $this;
    }


    /**
     * @param string $type
     * @return null|string
     */
    public function getManuelUpdateNoticeTypeLabel($type)
    {
        $helper = Mage::helper('contentsync');

        $labels = array(
            self::NOTICE_TYPE_CMS_BLOCK => $helper->__('Static Blocks'),
            self::NOTICE_TYPE_CMS_PAGE => $helper->__('Pages'),
            self::NOTICE_TYPE_EMAIL_TRANS => $helper->__('Transactional Emails'),
            self::NOTICE_TYPE_CORE_CONFIG => $helper->__('Configuration'),
        );

        if (array_key_exists($type, $labels)) {
            return $labels[$type];
        }

        return null;
    }


    /**
     * @param string $type
     * @return null|string Update action URL
     */
    public function getManuelUpdateNoticeTypeUrl($type)
    {
        $backUrl = Mage::helper('core/url')->getCurrentBase64Url();

        $urls = array(
            self::NOTICE_TYPE_CMS_BLOCK => Mage_Adminhtml_Helper_Data::getUrl('adminhtml/contentsync_export/export', array('content' => self::NOTICE_TYPE_CMS_BLOCK, 'back' => $backUrl)),
            self::NOTICE_TYPE_CMS_PAGE => Mage_Adminhtml_Helper_Data::getUrl('adminhtml/contentsync_export/export', array('content' => self::NOTICE_TYPE_CMS_PAGE, 'back' => $backUrl)),
            self::NOTICE_TYPE_EMAIL_TRANS => Mage_Adminhtml_Helper_Data::getUrl('adminhtml/contentsync_export/export', array('content' => self::NOTICE_TYPE_EMAIL_TRANS, 'back' => $backUrl)),
            self::NOTICE_TYPE_CORE_CONFIG => Mage_Adminhtml_Helper_Data::getUrl('adminhtml/contentsync_export/export', array('content' => self::NOTICE_TYPE_CORE_CONFIG, 'back' => $backUrl)),
        );

        if (array_key_exists($type, $urls)) {
            return $urls[$type];
        }

        return null;
    }


}