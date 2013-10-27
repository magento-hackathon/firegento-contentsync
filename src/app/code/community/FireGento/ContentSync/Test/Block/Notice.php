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
 * Notice Block
 *
 * @category FireGento
 * @package  FireGento_ContentSync
 * @author   FireGento Team <team@firegento.com>
 */

class FireGento_ContentSync_Test_Block_Notice extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @var FireGento_ContentSync_Block_Notice
     */
    protected $model = NULL;

    /**
     *
     */
    protected function setUp()
    {
        $this->model = Mage::getSingleton('core/layout')->createBlock('contentsync/notice');
    }

    /**
     * replaces singleton contentsync/notice with mock
     *
     * @param $methodName mocked method
     * @param $returnValue return value of method
     * @param $with with parameter for mock
     */
    private function replaceSingletonNoticeMock($methodName, $returnValue, $with = NULL)
    {
        $noticeMock = $this->getModelMock('contentsync/notice', array($methodName));
        $method = $noticeMock
            ->expects($this->once())
            ->method($methodName);

        if (!is_null($with)) {
            $method = $method->with($with);
        }

        $method->will($this->returnValue($returnValue));
        $this->replaceByMock('singleton', 'contentsync/notice', $noticeMock);
    }

    public function testGetNotices()
    {
        $this->assertModelReturnsSingletonNoticeMockValue(
            'getNotices',
            'getNoticeFlag'
        );
    }

    public function testGetLabel()
    {
        $this->assertModelReturnsSingletonNoticeMockValue(
            'getLabel',
            'getManualUpdateNoticeTypeLabel',
            'samplecode'
        );
    }

    /**
     * Assert that a called model method returns the value of the defined contentsync/notice singleton
     *
     * @param      $modelMethod
     * @param      $singletonMethod
     * @param null $param           for call modelMethod and singletonMethod
     */
    protected function assertModelReturnsSingletonNoticeMockValue($modelMethod, $singletonMethod, $param = NULL)
    {
        $this->replaceSingletonNoticeMock($singletonMethod, $param, $param);

        $this->assertEquals(
            $param,
            $this->model->$modelMethod($param)
        );
    }
}
