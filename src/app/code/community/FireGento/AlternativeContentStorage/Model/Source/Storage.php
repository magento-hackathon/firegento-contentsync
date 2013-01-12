<?php

class FireGento_AlternativeContentStorage_Model_Source_Storage
{

    const TYPE_FILE = 'file';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        // TODO: Get available storages from config file

        return array(
            array('value' => self::TYPE_FILE, 'label'=>Mage::helper('acs')->__('File')),
        );
    }

}
