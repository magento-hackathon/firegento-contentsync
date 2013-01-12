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
	public function testDataStoredInStorage() {
		$dataValues = array(
			array(
				'test'          => 'data',
				'creation_time' => 11,
				'update_time'   => 12,
			),
			array(
				'foo'           => 'bar',
				'creation_time' => 21,
				'update_time'   => 22,
			),
		);

		$model = $this->getModelMock(
			'acs/content_cms_page',
			array('storeDataInStorage')
		);

		$mocks = new FireGento_AlternativeContentStorage_Test_Model_Content_Abstract_Mocks($this);
		$classAlias = 'cms/page_collection';
		$resourceCmsPageCollection = $mocks->getResourceCollectionModelMock(
			$classAlias,
			$dataValues
		);
		$this->replaceByMock('resource_model', $classAlias, $resourceCmsPageCollection);

		// we don't store creation_time and update_time
		foreach ($dataValues as $k => $values) {
			unset($dataValues[$k]['creation_time']);
			unset($dataValues[$k]['update_time']);
		}
		$model
			->expects($this->once())
			->method('storeDataInStorage')
			->with($dataValues, 'cms_page');

		$model->storeData();
	}

	public function testLoadDataIteration() {
		$dataValues = array(
			array(
				'test'          => 'data',
				'page_id'       => 1,
			),
		    array(
			    'foo'           => 'bar',
			    'page_id'       => 2,
	    )
		);

		$cmsPageMock = $this->getModelMock(
			'cms/page',
			array('load', 'getId', 'setData', 'save')
		);
		$this->mockCmsPageMethods($cmsPageMock, 0, $dataValues[0]);
		$this->mockCmsPageMethods($cmsPageMock, 1, $dataValues[1]);
		$this->replaceByMock('model', 'cms/page', $cmsPageMock);

		$model = $this->getContentCmsPageModelMockLoadDataFromStorage($dataValues);

		$model->loadData();
	}

	/**
	 * mocks the load, getId, setData and save method of the cmsPageMock
	 * @param PHPUnit_Framework_MockObject_MockObject $cmsPageMock
	 * @param                                         $numberIteration
	 * @param                                         $data
	 */
	private function mockCmsPageMethods(PHPUnit_Framework_MockObject_MockObject $cmsPageMock, $numberIteration, $data) {
		$numberIteration *= 4;
		$cmsPageMock
			->expects($this->at($numberIteration++))
			->method('load')
			->with($data['page_id'])
			->will(
				$this->returnSelf()
			);
		$cmsPageMock
			->expects($this->at($numberIteration++))
			->method('getId')
			->will(
				$this->returnValue(true)
			);
		$cmsPageMock
			->expects($this->at($numberIteration++))
			->method('setData')
			->with($data)
			->will(
				$this->returnSelf()
			);
		$cmsPageMock
			->expects($this->at($numberIteration))
			->method('save');
	}

	/**
	 * gets a acs/content_cms_page mock with loadDataFromStorage method mocked
	 * @param $returnValue
	 * @return PHPUnit_Framework_MockObject_MockObject
	 */
	private function getContentCmsPageModelMockLoadDataFromStorage($returnValue) {
		$model = $this->getModelMock(
			'acs/content_cms_page',
			array('loadDataFromStorage')
		);
		$model
			->expects($this->once())
			->method('loadDataFromStorage')
			->will(
				$this->returnValue($returnValue)
			);
		return $model;
	}

	public function testLoadDataDisablesObserver() {
		$model = $this->getContentCmsPageModelMockLoadDataFromStorage(array());

		$observerMock = $this->getModelMock('acs/observer', array('disableObservers'));
		$observerMock
			->expects($this->once())
			->method('disableObservers');
		$this->replaceByMock('singleton', 'acs/observer', $observerMock);

		$model->loadData();

	}
}