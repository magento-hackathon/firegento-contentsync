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
 * Data Helper
 *
 * @category FireGento
 * @package  FireGento_ContentSync
 * @author   FireGento Team <team@firegento.com>
 */
class FireGento_ContentSync_Helper_Data extends Mage_Core_Helper_Abstract
{


    /**
     * @param  string $code
     * @return bool
     */
    public function isTriggerManually($code)
    {
        return (Mage::getStoreConfig('contentsync/content_' . $code . '/trigger') ==
            FireGento_ContentSync_Model_Source_Trigger::TRIGGER_MANUALLY);
    }


    /**
     * @param  string $code
     * @return bool
     */
    public function isTriggerAuto($code)
    {
        return (Mage::getStoreConfig('contentsync/content_' . $code . '/trigger') ==
            FireGento_ContentSync_Model_Source_Trigger::TRIGGER_AUTO);
    }


}
