<?php

class FireGento_ContentSync_Model_Storage_Format_Jsonhtml extends FireGento_ContentSync_Model_Storage_Format_Json implements FireGento_ContentSync_Model_Storage_Format_Interface
{

    public function encode( $data )
    {
        $result = '';
        foreach( $data AS $item )
        {
            $result .= $this->_exportItem($item);
        }

        return $result;
    }

    protected function _exportItem($item)
    {
        $content = $item['content'];
        unset($item['content']);

        $result = "############ NEW_ENTRY\n";

        $result .= $this->_prettyPrint( Zend_Json::encode($item) )."\n";
        $result .= "############ CONTENT\n";
        $result .= $content;

        return $result;
    }

    public function decode( $data )
    {
        $newline_pos = strpos($data, "\n\n");

        $json = Zend_Json::decode( substr( $data, 0, $newline_pos ) );
        $content = substr($data, $newline_pos);

        $json['content'] = $content;

        return $json;
    }

    public function getFilename($entity_code)
    {
        return $entity_code.'.jsonhtml';
    }

}