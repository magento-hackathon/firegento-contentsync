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
	 * set getConfig method with one expected call, with "storages" as parameter
	 * @param $returnValue which getConfig will return
	 */
	private function setConfigStorages($returnValue) {
		$this->model
			->expects($this->once())
			->method('getConfig')
			->with('storages')
			->will($this->returnValue($returnValue));
	}

	private function setUpStorageMocks($amount) {
		$mockStorage = $this->getModelMock('acs/storage_abstract');
		for ($i = 1; $i <= $amount; $i++) {
			$this->replaceByMock('model', 'acs/storage_' . $i, $mockStorage);
		}
	}

	/**
	 * sets up the config and storage mocks
	 * @param $configStorages value which will be returned by getConfig('storages')
	 * @param $amountOfStorageMocks amount of sotrage mocks to prepare
	 */
	private function setUpStorages($configStorages, $amountOfStorageMocks) {
		$this->setConfigStorages($configStorages);
		$this->setUpStorageMocks($amountOfStorageMocks);
	}

	public function testGetStoragesAmountOfStorages() {
		$this->setUpStorages(',1,2,', 2);

		$this->assertCount(
			2,
			$this->model->getStorages()
		);
	}

	/**
	 * @depends testGetStoragesAmountOfStorages
	 */
	public function testGetStoragesReturnInstance() {
		$this->setUpStorages('1', 1);

		$this->assertInstanceOf(
			'FireGento_AlternativeContentStorage_Model_Storage_Abstract',
			current($this->model->getStorages())
		);
	}

	/**
	 * @param $dataValues which are expected as first parameter on method storeData
	 * @return PHPUnit_Framework_MockObject_MockObject
	 */
	private function getStorageModelMock($dataValues) {
		$mock = $this->getMock('Varien_Object', array('storeData'));
		$mock
			->expects($this->once())
			->method('storeData')
			->with(
				$dataValues,
				'cms_page'
			);
		return $mock;
	}

	public function testStoreDataInStoragesIteration() {
		$dataValues = array('11', '22');

		// using a inherited class of Content_Abstract. protected storeDataInStorages is only called from them
		$model = $this->getModelMock(
			'acs/content_cms_page',
			array('getStorages')
		);
		$model
			->expects($this->once())
			->method('getStorages')
			->will(
				$this->returnValue(
					array(
					     $this->getStorageModelMock($dataValues),
					     $this->getStorageModelMock($dataValues),
					)
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
}