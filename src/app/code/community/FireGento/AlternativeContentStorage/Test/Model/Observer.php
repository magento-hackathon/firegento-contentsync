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

class FireGento_AlternativeContentStorage_Test_Model_Observer extends EcomDev_PHPUnit_Test_Case {
	/**
	 * @var FireGento_AlternativeContentStorage_Model_Observer
	 */
	protected $model = NULL;

	/**
	 * sets up the acs/observer
	 */
	protected function setUp() {
		$this->model = Mage::getModel('acs/observer');
	}

	/**
	 * get Varien_Event_Observer mocking hasDataChanges method
	 * @param $hasDataChanges return value of hasDataChanges method
	 * @return PHPUnit_Framework_MockObject_MockObject
	 */
	private function getMockEventObserver($hasDataChanges) {
		$mockObject = $this->getMock(
			'Varien_Object',
			array('hasDataChanges')
		);
		$mockObject
			->expects($this->once())
			->method('hasDataChanges')
			->will($this->returnValue($hasDataChanges));

		$mockEventObserver = $this->getMock(
			'Varien_Event_Observer',
			array('getObject')
		);
		$mockEventObserver
			->expects($this->once())
			->method('getObject')
			->will($this->returnValue($mockObject));

		return $mockEventObserver;
	}

	/**
	 * replaces singleton acs/content_cms_page with a mock
	 * mocking storeDataToFile method with expected $invokeCount
	 * @param PHPUnit_Framework_MockObject_Matcher_InvokedCount $invokeCount
	 */
	private function replaceSingletonContentCmsPageByMockWithStoreDataToFile(PHPUnit_Framework_MockObject_Matcher_InvokedCount $invokeCount) {
		$mockContentCmsPage = $this->getModelMock(
			'acs/content_cms_page',
			array('storeDataToFile')
		);
		$mockContentCmsPage
			->expects($invokeCount)
			->method('storeDataToFile');
		$this->replaceByMock('singleton', 'acs/content_cms_page', $mockContentCmsPage);
	}

	/**
	 * asserts null on afterCmsPageSave method
	 * @param                                                   $hasDataChanges for Varien_Object parameter on afterCmsPageSave method
	 * @param PHPUnit_Framework_MockObject_Matcher_InvokedCount $invokeCountStoreDataToFile invoke count for storeDataToFile method on acs/content_cms_page singleton mock
	 */
	private function assertNullOnAfterCmsPageSave($hasDataChanges, PHPUnit_Framework_MockObject_Matcher_InvokedCount $invokeCountStoreDataToFile) {
		$mockEventObserver = $this->getMockEventObserver($hasDataChanges);
		$this->replaceSingletonContentCmsPageByMockWithStoreDataToFile($invokeCountStoreDataToFile);

		$this->assertNull(
			$this->model->afterCmsPageSave($mockEventObserver)
		);
	}

	/**
	 * test functionality on method afterCmsPageSave
	 * - when data was not changed
	 * - storeDataToFile is NOT called
	 */
	public function testAfterCmsPageSaveWithoutDataChangedStoresNotToFile() {
		$hasDataChanges = false;
		$invokeCountStoreDataToFile = $this->never();
		$this->assertNullOnAfterCmsPageSave($hasDataChanges, $invokeCountStoreDataToFile);
	}

	/**
	 * test functionality on method afterCmsPageSave
	 * - when data was changed
	 * - storeDataToFile is called
	 */
	public function testAfterCmsPageSaveWithDataChangedStoresToFile() {
		$hasDataChanges = TRUE;
		$invokeCountStoreDataToFile = $this->once();
		$this->assertNullOnAfterCmsPageSave($hasDataChanges, $invokeCountStoreDataToFile);
	}
}