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
 * Do the Git Stuff
 *
 * @category FireGento
 * @package  FireGento_ContentSync
 * @author   FireGento Team <team@firegento.com>
 */

class FireGento_ContentSync_Model_Storage_Git extends FireGento_ContentSync_Model_Storage_File
{

    const DIRECTORY_CONFIG_PATH = 'contentsync/storage_git/directory';

    /**
     * @param array  $data
     * @param string $entityType
     */
    public function storeData($data, $entityType)
    {
        parent::storeData($data, $entityType);
        $fileName = $this->_getEntityFilename($entityType);
        $git = new VersionControl_Git( $this->_getStorageDirectory() );

        $fileName = substr( $fileName, strlen($this->_getStorageDirectory()) );

        try {

            $status = $git->getRevListFetcher();
            $status->setSubCommand('status');
            $status->addArgument('--porcelain');
            $status_result = $status->execute();

            foreach ( explode("\n", $status_result) AS $status_line ) {
                $git_status = substr($status_line, 1,1 );
                $git_file = substr($status_line, 3);

                if (  in_array( $git_status, array('M','A') ) ) {

                    $commit = $git->getCommand('commit');
                    $commit->setOption('message', 'current "'.$entityType.'" content' );
                    $commit->addArgument( $fileName );
                    $commit->execute();

                }

            }

        } catch ( VersionControl_Git_Exception $e ) {
            //if ( strpos($e->getMessage(), 'nothing to commit') === false )
            //{
                throw $e;
            //}
        }

    }

}
