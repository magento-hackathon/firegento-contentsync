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

class FireGento_AlternativeContentStorage_Model_Notice
{


    const NOTICE_REGISTER_KEY = 'notice_register_key';

    const NOTICE_TYPE_CMS_BLOCK = 'cms_block';
    const NOTICE_TYPE_CMS_PAGE = 'cms_page';
    const NOTICE_TYPE_EMAIL_TRANS = 'email_trans';
    const NOTICE_TYPE_CORE_CONFIG = 'core_config';


    static public function getSession()
    {
        return Mage::getSingleton('adminhtml/session');
    }


    /**
     * @param string
     */
    static public function showManuelUpdateNotice($type)
    {
        $types = self::getSession()->getData(self::NOTICE_REGISTER_KEY);
        $types = is_array($types) ? $types : array();
        $types[$type] = true;

        self::getSession()->setData(self::NOTICE_REGISTER_KEY, $types);
    }


    /**
     * @param string
     */
    static public function unsetManuelUpdateNotice($type)
    {
        $types = self::getSession()->getData(self::NOTICE_REGISTER_KEY);

        if (is_array($types)) {
            unset($types[$type]);
        }

        self::getSession()->setData(self::NOTICE_REGISTER_KEY, $types);
    }


    /**
     * @return array
     */
    static public function getManuelUpdateNoticeType()
    {
        $notices = self::getSession()->getData(self::NOTICE_REGISTER_KEY);
        $notices = is_array($notices) ? array_keys($notices) : array();

        return $notices;
    }


    /**
     *
     */
    static public function showManuelCmsBlockUpdateNotice()
    {
        self::showManuelUpdateNotice(self::NOTICE_TYPE_CMS_BLOCK);
    }


    /**
     *
     */
    static public function showManuelCmsPageUpdateNotice()
    {
        self::showManuelUpdateNotice(self::NOTICE_TYPE_CMS_PAGE);
    }


    /**
     *
     */
    static public function showManuelEmailTransUpdateNotice()
    {
        self::showManuelUpdateNotice(self::NOTICE_TYPE_EMAIL_TRANS);
    }


    /**
     *
     */
    static public function showManuelCoreConfigUpdateNotice()
    {
        self::showManuelUpdateNotice(self::NOTICE_TYPE_CORE_CONFIG);
    }


    /**
     * @param string $type
     * @return null|string
     */
    static public function getManuelUpdateNoticeTypeLabel($type)
    {
        $helper = Mage::helper('acs');

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
    static public function getManuelUpdateNoticeTypeUrl($type)
    {
        $backUrl = Mage::helper('core/url')->getCurrentBase64Url();

        $urls = array(
            self::NOTICE_TYPE_CMS_BLOCK => Mage_Adminhtml_Helper_Data::getUrl('adminhtml/acs_export/export', array('content' => self::NOTICE_TYPE_CMS_BLOCK, 'back' => $backUrl)),
            self::NOTICE_TYPE_CMS_PAGE => Mage_Adminhtml_Helper_Data::getUrl('adminhtml/acs_export/export', array('content' => self::NOTICE_TYPE_CMS_PAGE, 'back' => $backUrl)),
            self::NOTICE_TYPE_EMAIL_TRANS => Mage_Adminhtml_Helper_Data::getUrl('adminhtml/acs_export/export', array('content' => self::NOTICE_TYPE_EMAIL_TRANS, 'back' => $backUrl)),
            self::NOTICE_TYPE_CORE_CONFIG => Mage_Adminhtml_Helper_Data::getUrl('adminhtml/acs_export/export', array('content' => self::NOTICE_TYPE_CORE_CONFIG, 'back' => $backUrl)),
        );

        if (array_key_exists($type, $urls)) {
            return $urls[$type];
        }

        return null;
    }


}