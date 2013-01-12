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
class FireGento_AlternativeContentStorage_Helper_Data extends Mage_Core_Helper_Abstract
{


    const XML_PAHT_ACS_CONTENT_CMS_BLOCK_TRIGGER = 'acs/content_cms_block/trigger';
    const XML_PAHT_ACS_CONTENT_CMS_PAGE_TRIGGER = 'acs/content_cms_page/trigger';
    const XML_PAHT_ACS_CONTENT_EMAIL_TRANS_TRIGGER = 'acs/content_email_trans/trigger';


    /**
     * @return boolean
     */
    public function getCmsBlockTriggerAuto()
    {
        return (Mage::getStoreConfig(self::XML_PAHT_ACS_CONTENT_CMS_BLOCK_TRIGGER) == FireGento_AlternativeContentStorage_Model_Source_Trigger::TRIGGER_AUTO);
    }


    /**
     * @return boolean
     */
    public function getCmsBlockTriggerManually()
    {
        return (Mage::getStoreConfig(self::XML_PAHT_ACS_CONTENT_CMS_BLOCK_TRIGGER) == FireGento_AlternativeContentStorage_Model_Source_Trigger::TRIGGER_MANUALLY);
    }


    /**
     * @return boolean
     */
    public function getCmsPageTriggerAuto()
    {
        return (Mage::getStoreConfig(self::XML_PAHT_ACS_CONTENT_CMS_PAGE_TRIGGER) == FireGento_AlternativeContentStorage_Model_Source_Trigger::TRIGGER_AUTO);
    }


    /**
     * @return boolean
     */
    public function getCmsPageTriggerManually()
    {
        return (Mage::getStoreConfig(self::XML_PAHT_ACS_CONTENT_CMS_PAGE_TRIGGER) == FireGento_AlternativeContentStorage_Model_Source_Trigger::TRIGGER_MANUALLY);
    }


    /**
     * @return boolean
     */
    public function getEmailTransTriggerAuto()
    {
        return (Mage::getStoreConfig(self::XML_PAHT_ACS_CONTENT_EMAIL_TRANS_TRIGGER) == FireGento_AlternativeContentStorage_Model_Source_Trigger::TRIGGER_AUTO);
    }


    /**
     * @return boolean
     */
    public function getEmailTransTriggerManually()
    {
        return (Mage::getStoreConfig(self::XML_PAHT_ACS_CONTENT_EMAIL_TRANS_TRIGGER) == FireGento_AlternativeContentStorage_Model_Source_Trigger::TRIGGER_MANUALLY);
    }

}