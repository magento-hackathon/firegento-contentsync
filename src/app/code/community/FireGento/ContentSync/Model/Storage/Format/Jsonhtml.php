<?php

class FireGento_ContentSync_Model_Storage_Format_Jsonhtml extends FireGento_ContentSync_Model_Storage_Format_Json implements FireGento_ContentSync_Model_Storage_Format_Interface
{

    public function encode( $data )
    {
        $result = '';
        foreach( $data AS $item )
        {
            $result .= $this->_encodeItem($item);
        }

        return $result;
    }

    protected function _encodeItem($item)
    {
        $content = $item['content'];
        unset($item['content']);

        $result = "############ NEW_ENTRY\n";

        $result .= $this->_prettyPrint( Zend_Json::encode($item) )."\n";
        $result .= "############ CONTENT\n";
        $result .= $content;
        $result .= "\n";

        return $result;
    }

    public function decode( $data )
    {
        $result = array();
        $data = explode("\n", $data);
        $item = array();
        foreach( $data AS $row ) {

            if ( $row == '############ NEW_ENTRY' )
            {
                if ( count( $item ) ) {
                    $result[] = $this->_decodeItem( join('', $item) );
                }

                $item = array();
            } else {
                $item[] = $row;
            }

        }

        if ( count( $item ) ) {
            $result[] = $this->_decodeItem( join('', $item) );
        }


        return $result;
    }

    /**
     * @param $data string
     */
    protected function _decodeItem($data)
    {

        list($json_str, $content) = explode('############ CONTENT', $data);
        $json = Zend_Json::decode($json_str);
        if ( $content ) {
            $json['content'] = $content;
        }
        return $json;
    }


    public function getFilename($entity_code)
    {
        return $entity_code.'.jsonhtml';
    }

}