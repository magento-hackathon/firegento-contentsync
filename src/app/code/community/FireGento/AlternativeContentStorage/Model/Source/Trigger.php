<?php

class FireGento_AlternativeContentStorage_Model_Source_Trigger
{

    const TRIGGER_DISABLED = 0;
    const TRIGGER_AUTO = 1;
    const TRIGGER_MANUALLY = 2;

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
