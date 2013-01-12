<?php

/**
 * This file is part of the FIREGENTO project.
 * 
 * Copyright (c) 2013 mzentrale | eCommerce - eBusiness
 * 
 * FireGento_AlternativeContentStorage is free software; you can redistribute it and/or
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

/**
 * Config Unit Tests
 * 
 * @category  FireGento
 * @package   FireGento_AlternativeContentStorage
 * @author    FireGento Team <team@firegento.com>
 */
class FireGento_AlternativeContentStorage_Test_Config_Main extends EcomDev_PHPUnit_Test_Case_Config
{

    public function testModelAlias()
    {
        $this->assertModelAlias('acs/storage_file', 'FireGento_AlternativeContentStorage_Model_Storage_File');
	    $this->assertModelAlias('acs/observer', 'FireGento_AlternativeContentStorage_Model_Observer');
	    $this->assertModelAlias('acs/source_storage', 'FireGento_AlternativeContentStorage_Model_Source_Storage');
	    $this->assertModelAlias('acs/email', 'FireGento_AlternativeContentStorage_Model_Email');
	    $this->assertModelAlias('acs/cms_block', 'FireGento_AlternativeContentStorage_Model_Cms_Block');
	    $this->assertModelAlias('acs/cms_page', 'FireGento_AlternativeContentStorage_Model_Cms_Page');
    }

    public function testHelperAlias()
    {
        $this->assertHelperAlias('acs', 'FireGento_AlternativeContentStorage_Helper_Data');
        $this->assertHelperAlias('acs/data', 'FireGento_AlternativeContentStorage_Helper_Data');
    }
}
