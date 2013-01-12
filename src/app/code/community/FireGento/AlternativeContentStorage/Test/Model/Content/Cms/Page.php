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

class FireGento_AlternativeContentStorage_Test_Model_Content_Cms_Page extends EcomDev_PHPUnit_Test_Case {
	/**
	 * @var FireGento_AlternativeContentStorage_Model_Content_Cms_Page
	 */
	protected $model = NULL;

	/**
	 * sets up the acs/observer
	 */
	protected function setUp() {
		$this->model = Mage::getModel('acs/content_cms_page');
	}

	/**
	 * gets a varien_object mock with mocked getData
	 * @param $returnValue for getData method
	 * @return PHPUnit_Framework_MockObject_MockObject
	 */
	private function getObjectMockGetData($returnValue) {
		$mock = $this->getMock(
			'Varien_Object',
			array('getData')
		);
		$mock
			->expects($this->once())
			->method('getData')
			->will($this->returnValue($returnValue));
		return $mock;
	}

	/**
	 * replaces a model resource collection
	 * @param $classAlias
	 * @param $iteratorValues
	 */
	private function replaceResourceCollectionModelMock($classAlias, $iteratorValues) {
		$resourceCmsPageCollection = $this->getResourceModelMock(
			$classAlias,
			array(),
			FALSE,
			array(),
			'',
			FALSE
		);
		$resourceCmsPageCollection
			->expects($this->any())
			->method('getIterator')
			->will(
				$this->returnValue(
					new ArrayIterator($iteratorValues)
				)
		);
		$this->replaceByMock('resource_model', $classAlias, $resourceCmsPageCollection);
	}

	public function testDataStoredInStorages() {
		$dataValues = array('11', '22');
		$model = $this->getModelMock(
			'acs/content_cms_page',
			array('storeDataInStorages')
		);
		$model
			->expects($this->once())
			->method('storeDataInStorages')
			->with($dataValues, 'cms_page');

		$mockPage1 = $this->getObjectMockGetData($dataValues[0]);
		$mockPage2 = $this->getObjectMockGetData($dataValues[1]);
		$this->replaceResourceCollectionModelMock(
			'cms/page_collection',
			array(
			     $mockPage1,
			     $mockPage2)
		);

		$model->storeData();
	}
}