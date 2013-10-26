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

class FireGento_ContentSync_Test_Model_Content_Abstract_Mocks
{
    /**
     * @var EcomDev_PHPUnit_Test_Case
     */
    private $testCase;

    /**
     * @param EcomDev_PHPUnit_Test_Case $testCase
     */
    public function __construct(EcomDev_PHPUnit_Test_Case $testCase)
    {
        $this->testCase = $testCase;
    }

    /**
     * gets a varien_object mock with mocked getData
     * @param $returnValue for getData method
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getObjectMockGetData($returnValue)
    {
        $mock = $this->testCase->getMock(
            'Varien_Object',
            array('getData')
        );
        $mock
            ->expects($this->testCase->once())
            ->method('getData')
            ->will($this->testCase->returnValue($returnValue));
        return $mock;
    }

    /**
     * replaces a model resource collection
     * @param $classAlias
     * @param $dataValues
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    public function getResourceCollectionModelMock($classAlias, $dataValues)
    {
        $mockPage1 = $this->getObjectMockGetData($dataValues[0]);
        $mockPage2 = $this->getObjectMockGetData($dataValues[1]);
        $iteratorValues = array($mockPage1, $mockPage2);

        $resourceCmsPageCollection = $this->testCase->getResourceModelMock(
            $classAlias,
            array(),
            FALSE,
            array(),
            '',
            FALSE
        );
        $resourceCmsPageCollection
            ->expects($this->testCase->any())
            ->method('getIterator')
            ->will(
                $this->testCase->returnValue(
                    new ArrayIterator($iteratorValues)
                )
        );
        return $resourceCmsPageCollection;
    }
}
