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


    /**
     * @return array
     */
    public function getNotices()
    {
        return FireGento_AlternativeContentStorage_Model_Notice::getManuelUpdateNoticeType();
    }


    /**
     * @param string $type
     * @return mixed
     */
    public function getLabel($type)
    {
        return FireGento_AlternativeContentStorage_Model_Notice::getManuelUpdateNoticeTypeLabel($type);
    }


    /**
     * @param string $type
     * @return mixed
     */
    public function getExportUrl($type)
    {
        return FireGento_AlternativeContentStorage_Model_Notice::getManuelUpdateNoticeTypeUrl($type);
    }


    /**
     * @param string $type
     * @return mixed
     */
    public function getIgnoreUrl($type)
    {
        $backUrl = Mage::helper('core/url')->getCurrentBase64Url();
        return Mage_Adminhtml_Helper_Data::getUrl('adminhtml/acs_export/close', array('content' => $type, 'back' => $backUrl));
    }


}
