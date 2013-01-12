<?php

/**
 * This file is part of the FIREGENTO project.
 * 
 * Copyright (c) 2013 mzentrale | eCommerce - eBusiness
 * 
 * FireGento_ContentSync is free software; you can redistribute it and/or
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

/**
 * Config Unit Tests
 * 
 * @category  FireGento
 * @package   FireGento_ContentSync
 * @author    FireGento Team <team@firegento.com>
 */
class FireGento_ContentSync_Test_Config_Main extends EcomDev_PHPUnit_Test_Case_Config
{

    public function testModelAlias()
    {
        $this->assertModelAlias('contentsync/storage_file', 'FireGento_ContentSync_Model_Storage_File');
	    $this->assertModelAlias('contentsync/observer', 'FireGento_ContentSync_Model_Observer');
	    $this->assertModelAlias('contentsync/source_storage', 'FireGento_ContentSync_Model_Source_Storage');
	    $this->assertModelAlias('contentsync/source_trigger', 'FireGento_ContentSync_Model_Source_Trigger');
	    $this->assertModelAlias('contentsync/content_email', 'FireGento_ContentSync_Model_Content_Email');
	    $this->assertModelAlias('contentsync/content_cms_block', 'FireGento_ContentSync_Model_Content_Cms_Block');
	    $this->assertModelAlias('contentsync/content_cms_page', 'FireGento_ContentSync_Model_Content_Cms_Page');
    }

    public function testHelperAlias()
    {
        $this->assertHelperAlias('contentsync', 'FireGento_ContentSync_Helper_Data');
        $this->assertHelperAlias('contentsync/data', 'FireGento_ContentSync_Helper_Data');
    }

    public function testModuleVersion()
    {
        $this->assertModuleVersionGreaterThanOrEquals('0.1.0');
        $this->assertModuleVersionGreaterThanOrEquals('0.2.0');
    }
}
