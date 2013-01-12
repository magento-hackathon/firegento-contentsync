<?php
/**
 * This file is part of the FIREGENTO project.
 *
 * FireGento_AlternativeContentStorage is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 3 as
 * published by the Free Software Foundation.
 *
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * @category  FireGento
 * @package   FireGento_AlternativeContentStorage
 * @author    FireGento Team <team@firegento.com>
 * @copyright 2013 FireGento Team (http://www.firegento.de). All rights served.
 * @license   http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version   $Id:$
 * @since     0.1.0
 */

class FireGento_AlternativeContentStorage_Test_Model_Content_Abstract extends EcomDev_PHPUnit_Test_Case {
	/**
	 * @var FireGento_AlternativeContentStorage_Model_Content_Cms_Abstract
	 */
	protected $model = NULL;

	/**
	 * sets up the acs/content_cms_abstract
	 */
	protected function setUp() {
		// mock it, because class is abstract
		$this->model = $this->getModelMock('acs/content_cms_abstract', array('getConfig'));
	}

	/**
	 * set getConfig method with one expected call, with "storage" as parameter
	 * @param $returnValue which getConfig will return
	 */
	private function setConfigStorage($returnValue) {
		$this->model
			->expects($this->once())
			->method('getConfig')
			->with('storage')
			->will($this->returnValue($returnValue));
	}

	/**
	 * @param $type storage type
	 */
	private function setUpStorageMock($type) {
		$mockStorage = $this->getModelMock('acs/storage_abstract');
		$this->replaceByMock('model', 'acs/storage_' . $type, $mockStorage);
	}

	/**
	 * sets up the config and storage mocks
	 * @param $configStorage value which will be returned by getConfig('storage')
	 * @param $storageType storage type
	 */
	private function setUpStorage($configStorage, $storageType) {
		$this->setConfigStorage($configStorage);
		$this->setUpStorageMock($storageType);
	}

	public function testGetStorageReturnInstance() {
		$this->setUpStorage('foobar', 'foobar');

		$this->assertInstanceOf(
			'FireGento_AlternativeContentStorage_Model_Storage_Abstract',
			$this->model->getStorage()
		);
	}

	public function testGetStorageVoid() {
		$this->assertInstanceOf(
			'FireGento_AlternativeContentStorage_Model_Storage_Void',
			$this->model->getStorage()
		);
	}

	public function testStoreDataInStorage() {
		$dataValues = array(
			array(
				'creation_date' => 11,
				'update_date'   => 12,
			),
			array(
				'creation_date' => 21,
				'update_date'   => 22,
			)
		);

		$mockStorage = $this->getMock('Varien_Object', array('storeData'));
		$mockStorage
			->expects($this->once())
			->method('storeData')
			->with(
				$dataValues,
				'cms_page'
			);

		// using a inherited class of Content_Abstract. protected storeDataInStorage is only called from them
		$model = $this->getModelMock(
			'acs/content_cms_page',
			array('getStorage')
		);
		$model
			->expects($this->once())
			->method('getStorage')
			->will(
				$this->returnValue(
				     $mockStorage
				)
			);

		$mocks = new FireGento_AlternativeContentStorage_Test_Model_Content_Abstract_Mocks($this);
		$classAlias = 'cms/page_collection';
		$resourceCmsPageCollection = $mocks->getResourceCollectionModelMock(
			$classAlias,
			$dataValues
		);
		$this->replaceByMock('resource_model', $classAlias, $resourceCmsPageCollection);

		$this->assertNull(
			$model->storeData()
		);
	}

	public function testLoadDataFromStorage() {
		$mockStorage = $this->getMock('Varien_Object', array('loadData'));
		$mockStorage
			->expects($this->once())
			->method('loadData')
			->with(
				'cms_page'
			)
			->will($this->returnValue(array()));

		// using a inherited class of Content_Abstract. protected storeDataInStorage is only called from them
		$model = $this->getModelMock(
			'acs/content_cms_page',
			array('getStorage')
		);
		$model
			->expects($this->once())
			->method('getStorage')
			->will(
				$this->returnValue(
				     $mockStorage
				)
			);

		$this->assertNull(
			$model->loadData()
		);
	}
}