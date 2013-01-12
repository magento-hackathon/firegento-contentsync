<?php

class FireGento_AlternativeContentStorage_Model_Source_Trigger
{

    const TRIGGER_DISABLED = 'disabled';
    const TRIGGER_AUTO = 'auto';
    const TRIGGER_MANUALLY = 'manual';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => self::TRIGGER_DISABLED,    'label'=>Mage::helper('acs')->__('Disabled') ),
            array('value' => self::TRIGGER_AUTO,        'label'=>Mage::helper('acs')->__('Automatically') ),
            array('value' => self::TRIGGER_MANUALLY,    'label'=>Mage::helper('acs')->__('Manually') )
        );
    }

}
