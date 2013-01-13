<?php

interface FireGento_ContentSync_Model_Storage_Format_Interface
{

    /**
     * @param $data
     * @return string
     */
    public function encode( $data );

    /**
     * @param $data
     * @return string
     */
    public function decode( $data );

    /**
     * @param $entity_type
     * @return string
     */
    public function getFilename( $entity_type );


}