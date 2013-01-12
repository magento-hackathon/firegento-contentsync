<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vfranz
 * Date: 12.01.13
 * Time: 17:51
 * To change this template use File | Settings | File Templates.
 */
class FireGento_AlternativeContentStorage_Block_Notice extends Mage_Adminhtml_Block_Abstract
{


    protected function _construct()
    {
        parent::_construct();

        $type = FireGento_AlternativeContentStorage_Model_Notice::getManuelUpdateNoticeType();


        $this->setLabel(FireGento_AlternativeContentStorage_Model_Notice::getManuelUpdateNoticeTypeLabel($type));
        $this->setActionUrl(FireGento_AlternativeContentStorage_Model_Notice::getManuelUpdateNoticeTypeUrl($type));

        FireGento_AlternativeContentStorage_Model_Notice::unsetManuelUpdateNotice();

    }


}
