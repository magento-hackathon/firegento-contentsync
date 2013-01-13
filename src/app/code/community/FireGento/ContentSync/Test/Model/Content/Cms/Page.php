<?php
/**
 * This file is part of the FIREGENTO project.
 *
 * FireGento_ContentSync is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 3 as
 * published by the Free Software Foundation.
 *
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * @category  FireGento
 * @package   FireGento_ContentSync
 * @author    FireGento Team <team@firegento.com>
 * @copyright 2013 FireGento Team (http://www.firegento.de). All rights served.
 * @license   http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version   $Id:$
 * @since     0.1.0
 */

class FireGento_ContentSync_Test_Model_Content_Cms_Page extends EcomDev_PHPUnit_Test_Case {
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
			'contentsync/content_cms_page',
			array('storeDataInStorage')
		);

		$mocks = new FireGento_ContentSync_Test_Model_Content_Abstract_Mocks($this);
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

	public function testLoadDataNewUpdatesTable() {
		$dataValues = array(
			array(
				'test'          => 'data',
				'page_id'       => 1,
			),
		);

		$cmsPageMock = $this->getModelMock(
			'cms/page',
			array('load', 'getId', 'setData', 'save')
		);
		$this->mockCmsPageMethods($cmsPageMock, 0, $dataValues[0], FALSE);
		$this->replaceByMock('model', 'cms/page', $cmsPageMock);

		$model = $this->getContentCmsPageModelMockLoadDataFromStorage(
			$dataValues,
			array('loadDataFromStorage', '_deletePagesNotImported')
		);

		$cmsPageTableName = 'cmsPageTable';

		$connectionMock = $this->getMock(
			'Varien_Db_Adapter_Pdo_Mysql',
			array('update'),
			array(),
			'',
			FALSE
		);
		$connectionMock
			->expects($this->once())
			->method('update')
			->with(
				$cmsPageTableName,
				array('page_id' => $dataValues[0]['page_id']),
				'page_id = ' . $dataValues[0]['page_id'] . '1'
			);

		$coreResourceMock = $this->getModelMock(
			'core/resource',
			array(
			    'getConnection',
				'getTableName'
			)
		);
		$coreResourceMock
			->expects($this->once())
			->method('getConnection')
			->with('core/write')
			->will($this->returnValue($connectionMock));
		$coreResourceMock
			->expects($this->once())
			->method('getTableName')
			->with('cms/page')
			->will($this->returnValue($cmsPageTableName));
		$this->replaceByMock('singleton', 'core/resource', $coreResourceMock);

		$model->loadData();

	}

	/**
	 * mocks the load, getId, setData and save method of the cmsPageMock
	 * @param PHPUnit_Framework_MockObject_MockObject $cmsPageMock
	 * @param                                         $numberIteration
	 * @param                                         $data
	 * @param                                         $returnGetId return value of getId method
	 */
	private function mockCmsPageMethods(PHPUnit_Framework_MockObject_MockObject $cmsPageMock, $numberIteration, $data, $returnGetId = true) {
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
				$this->returnValue($returnGetId)
			);
		if (!$returnGetId) {
			$dataPageId = $data['page_id'];
			unset($data['page_id']);
		}
		$cmsPageMock
			->expects($this->at($numberIteration++))
			->method('setData')
			->with($data)
			->will(
				$this->returnSelf()
			);
		$cmsPageMock
			->expects($this->at($numberIteration++))
			->method('save');
		if (!$returnGetId) {
			$cmsPageMock
				->expects($this->at($numberIteration))
				->method('getId')
				->will(
					$this->returnValue($dataPageId . '1')
				);
		}
	}

	/**
	 * gets a contentsync/content_cms_page mock with loadDataFromStorage method mocked
	 * @param $returnValue of loadDataFromStorage
	 * @param $methods to mock
	 * @return PHPUnit_Framework_MockObject_MockObject
	 */
	private function getContentCmsPageModelMockLoadDataFromStorage($returnValue, $methods = array('loadDataFromStorage')) {
		$model = $this->getModelMock(
			'contentsync/content_cms_page',
			$methods
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

		$observerMock = $this->getModelMock('contentsync/observer', array('disableObservers'));
		$observerMock
			->expects($this->once())
			->method('disableObservers');
		$this->replaceByMock('singleton', 'contentsync/observer', $observerMock);

		$model->loadData();

	}
}