<?php

class FireGento_ContentSync_Model_Storage_Format_Idfield extends FireGento_ContentSync_Model_Storage_Format_Json implements FireGento_ContentSync_Model_Storage_Format_Interface
{

    public function encode( $data )
    {
        $content = $data['content'];
        unset( $data['content'] );

        $result = $this->_prettyPrint( Zend_Json::encode($data) );
        $result .= "\n\n";
        $result .= $content;
        return $result;
    }

    public function decode( $data )
    {
        list($json_str, $content) = explode("\n\n", $data);
        $json = Zend_Json::decode($json_str);
        if ( $content ) {
            $json['content'] = $content;
        }
        return $json;
    }

    public function getFilename($entity_code)
    {
        return null;
    }

}