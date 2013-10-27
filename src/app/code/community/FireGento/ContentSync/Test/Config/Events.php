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
 * Events Config
 *
 * @category FireGento
 * @package  FireGento_ContentSync
 * @author   FireGento Team <team@firegento.com>
 */

class FireGento_ContentSync_Test_Config_Events extends EcomDev_PHPUnit_Test_Case_Config
{
    public function testCmsPageSaveAfter()
    {
        $this->assertEventObserverDefined('global',
            'model_save_after',
            'contentsync/observer',
            'afterObjectSave',
            'contentsync');
        $this->assertEventObserverDefined('global',
            'model_save_before',
            'contentsync/observer',
            'beforeObjectSave',
            'contentsync_observer');
    }
}
