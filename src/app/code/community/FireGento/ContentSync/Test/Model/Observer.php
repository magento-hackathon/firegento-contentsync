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

class FireGento_ContentSync_Test_Model_Observer extends EcomDev_PHPUnit_Test_Case {
	/**
	 * @var FireGento_ContentSync_Model_Observer
	 */
	protected $model = NULL;

	/**
	 * sets up the contentsync/observer
	 */
	protected function setUp() {
		$this->model = Mage::getModel('contentsync/observer');

		$helper = $this->getHelperMock('contentsync/data', array('isTriggerAuto'));
		$helper
			->expects($this->any())
			->method('isTriggerAuto')
			->will($this->returnValue(true));
		$this->replaceByMock('helper', 'contentsync', $helper);
	}

	/**
	 * get Varien_Event_Observer mocking hasDataChanges method
	 * @param $hasDataChanges return value of hasDataChanges method
	 * @return PHPUnit_Framework_MockObject_MockObject
	 */
	private function getMockEventObserver($hasDataChanges) {
		$mockObject = $this->getMock(
			'Mage_Cms_Model_Page',
			array('hasDataChanges')
		);
		$mockObject
			->expects($this->once())
			->method('hasDataChanges')
			->will($this->returnValue($hasDataChanges));

		$mockEvent = $this->getMock(
			'Varien_Event',
			array('getObject')
		);
		$mockEvent
			->expects($this->once())
			->method('getObject')
			->will($this->returnValue($mockObject));

		$mockEventObserver = $this->getMock(
			'Varien_Event_Observer',
			array('getEvent')
		);
		$mockEventObserver
			->expects($this->once())
			->method('getEvent')
			->will($this->returnValue($mockEvent));

		return $mockEventObserver;
	}

	/**
	 * replaces singleton $classAlias with a mock
	 * mocking storeData method with expected $invokeCount
	 * @param $classAlias
	 * @param PHPUnit_Framework_MockObject_Matcher_InvokedCount $invokeCount
	 */
	private function replaceSingletonByMockWithStoreData($classAlias, PHPUnit_Framework_MockObject_Matcher_InvokedCount $invokeCount) {
		$mockContentCmsPage = $this->getModelMock(
			$classAlias,
			array('storeData')
		);
		$mockContentCmsPage
			->expects($invokeCount)
			->method('storeData');
		$this->replaceByMock('singleton', $classAlias, $mockContentCmsPage);
	}

	/**
	 * asserts null on afterObjectSave method
	 * @param                                                   $hasDataChanges for Varien_Object parameter on afterObjectSave method
	 * @param PHPUnit_Framework_MockObject_Matcher_InvokedCount $invokeCountStoreData invoke count for storeData method on contentsync/content_cms_page singleton mock
	 */
	private function assertNullOnAfterObjectSave($hasDataChanges, PHPUnit_Framework_MockObject_Matcher_InvokedCount $invokeCountStoreData) {
		$mockEventObserver = $this->getMockEventObserver($hasDataChanges);
		$this->replaceSingletonByMockWithStoreData('contentsync/content_flat', $invokeCountStoreData);

		$this->assertNull(
			$this->model->afterObjectSave($mockEventObserver)
		);
	}

	/**
	 * test functionality on method afterObjectSave
	 * - when data was not changed
	 * - storeData is NOT called
	 */
	public function testAfterObjectSaveWithoutDataChangedStoresNot() {
		$hasDataChanges = false;
		$invokeCountStoreData = $this->never();
		$this->assertNullOnAfterObjectSave($hasDataChanges, $invokeCountStoreData);
	}

	/**
	 * test functionality on method afterObjectSave
	 * - when data was changed
	 * - storeData is called
	 */
	public function testAfterObjectSaveWithDataChangedStores() {
		$hasDataChanges = TRUE;
		$invokeCountStoreData = $this->once();
		$this->assertNullOnAfterObjectSave($hasDataChanges, $invokeCountStoreData);
	}
}