<?php

/**
 * Copyright 2010 Kousuke Ebihara
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * PHP Version 5
 *
 * @category  VersionControl
 * @package   VersionControl_Git
 * @author    Kousuke Ebihara <ebihara@php.net>
 * @copyright 2010 Kousuke Ebihara
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

/**
 * The base class of all components for VersionControl_Git
 *
 * Components must be inherit from this to be related to an instance of
 * the VersionControl_Git.
 *
 * @category  VersionControl
 * @package   VersionControl_Git
 * @author    Kousuke Ebihara <ebihara@php.net>
 * @copyright 2010 Kousuke Ebihara
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */
abstract class VersionControl_Git_Component
{
    /**
     * The related instance of the VersionControl_Git
     *
     * @var VersionControl_Git
     */
    protected $git;

    /**
     * Constructor
     *
     * @param VersionControl_Git $git An instance of the VersionControl_Git
     */
    public function __construct(VersionControl_Git $git)
    {
        $this->git = $git;
    }
}
