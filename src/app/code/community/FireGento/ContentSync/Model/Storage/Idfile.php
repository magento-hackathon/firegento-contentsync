<?php

class FireGento_ContentSync_Model_Storage_Idfile extends FireGento_ContentSync_Model_Storage_File
{

    public function getFormat()
    {
        return new FireGento_ContentSync_Model_Storage_Format_Idfield();
    }

    protected function _checkDir($directoryPath)
    {
        if (!is_dir($directoryPath)) {
            if (!mkdir($directoryPath, 0777, true)) {
                Mage::throwException(
                    Mage::helper('contentsync')->__('Directory "%s" could not be created.', $directoryPath)
                );
            }
        }

        if (!is_dir_writeable($directoryPath)) {
            Mage::throwException(
                Mage::helper('contentsync')->__('Directory "%s" is not writable.', $directoryPath)
            );
        }

        if (!in_array(substr($directoryPath, -1 , 1), array('/', '\\'))) {
            $directoryPath .= DS;
        }

        return $directoryPath;
    }

    protected function _getStorageDirectory()
    {
        $directoryPath = Mage::getStoreConfig( self::DIRECTORY_CONFIG_PATH );
        return $this->_checkDir( $directoryPath );
    }

    /**
     * @param array $data
     * @param string $entityType
     */
    public function storeData($data, $entityType) {

        foreach( $data AS $item ) {

            $fileContent = $this->getFormat()->encode($item);
            $fileName = $this->_checkDir( $this->_getStorageDirectory().$entityType.DS) . $item[ $this->getIdField() ].".json";

            if (file_put_contents($fileName, $fileContent) === false) {
                Mage::throwException(
                    Mage::helper('contentsync')->__('File "%s" could not be written.', $fileName)
                );
            }

        }

    }

    public function loadData($entityType)
    {
        $dir = $this->_checkDir( $this->_getStorageDirectory().$entityType.DS);
        $data = array();
        foreach( glob( $dir."*.json" ) AS $file )
        {
            $filedata = file_get_contents( $file );
            $data[] = $this->getFormat()->decode($filedata);
        }
        return $data;
    }

}