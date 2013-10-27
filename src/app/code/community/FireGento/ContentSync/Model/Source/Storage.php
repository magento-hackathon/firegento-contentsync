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
 * Source Storage
 *
 * @category FireGento
 * @package  FireGento_ContentSync
 * @author   FireGento Team <team@firegento.com>
 */

class FireGento_ContentSync_Model_Source_Storage
{

    const TYPE_FILE = 'file';
    const TYPE_GIT = 'git';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        // TODO: Get available storages from config file

        return array(
            array('value' => self::TYPE_FILE, 'label'=>Mage::helper('contentsync')->__('File')),
            array('value' => self::TYPE_GIT, 'label'=>Mage::helper('contentsync')->__('Git')),
        );
    }

}
