<?php

class FireGento_AlternativeContentStorage_Model_Source_Storage
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        // TODO: Get available storages from config file

        return array(
            array('value' => '',        'label'=>Mage::helper('adminhtml')->__('None')),
            array('value' => 'File',    'label'=>Mage::helper('adminhtml')->__('File')),
        );
    }

}
