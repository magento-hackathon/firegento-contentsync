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
 * Source Trigger Class
 *
 * @category FireGento
 * @package  FireGento_ContentSync
 * @author   FireGento Team <team@firegento.com>
 */

class FireGento_ContentSync_Model_Source_Trigger
{

    const TRIGGER_DISABLED = 'disabled';
    const TRIGGER_AUTO = 'auto';
    const TRIGGER_MANUALLY = 'manual';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => self::TRIGGER_DISABLED, 'label'=>Mage::helper('contentsync')->__('Disabled') ),
            array('value' => self::TRIGGER_AUTO,     'label'=>Mage::helper('contentsync')->__('Automatically') ),
            array('value' => self::TRIGGER_MANUALLY, 'label'=>Mage::helper('contentsync')->__('Manually') )
        );
    }

}
