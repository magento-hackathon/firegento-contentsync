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
 * Do the SVN Stuff
 *
 * @category FireGento
 * @package  FireGento_ContentSync
 * @author   FireGento Team <team@firegento.com>
 */

class FireGento_ContentSync_Model_Storage_Svn extends FireGento_ContentSync_Model_Storage_File
{

    const DIRECTORY_CONFIG_PATH = 'contentsync/storage_svn/directory';

    /**
     * @param  array                        $data
     * @param  string                       $entityType
     * @throws VersionControl_SVN_Exception on error
     */
    public function storeData($data, $entityType)
    {
        parent::storeData($data, $entityType);
        $fileName = $this->_getEntityFilename($entityType);

        $svnStatus = VersionControl_SVN::factory('status');
        $status = $svnStatus->run(array($fileName));

        if (isset($status['target']) AND
            isset($status['target'][0]) AND
            isset($status['target'][0]['entry']) AND
            is_array($status['target'][0]['entry'])) {
                foreach ($status['target'][0]['entry'] as $entry) {
                    switch ($entry['wc-status']['item']) {
                        case 'unversioned':
                            $svnAdd = VersionControl_SVN::factory('add');
                            $svnAdd->run(array($fileName));
                        case 'modified':
                        case 'added':
                            $svnCommit = VersionControl_SVN::factory('ci');
                            $svnCommit->run(
                                array($fileName),
                                array(
                                     'm' => escapeshellarg('current "'.$entityType.'" content'),
                                )
                            );
                    }
                }
            }
    }

}
