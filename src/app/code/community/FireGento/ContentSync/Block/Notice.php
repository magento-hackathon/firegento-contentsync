<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vfranz
 * Date: 12.01.13
 * Time: 17:51
 * To change this template use File | Settings | File Templates.
 */
class FireGento_ContentSync_Block_Notice extends Mage_Adminhtml_Block_Abstract
{


    /**
     * @return array
     */
    public function getNotices()
    {
        return Mage::getSingleton('contentsync/notice')->getNoticeFlag();
    }


    /**
     * @param string $type
     * @return mixed
     */
    public function getLabel($type)
    {
        return Mage::getSingleton('contentsync/notice')->getManualUpdateNoticeTypeLabel($type);
    }


    /**
     * @param string $type
     * @return mixed
     */
    public function getExportUrl($type)
    {
        return Mage::getSingleton('contentsync/notice')->getManualUpdateNoticeTypeUrl($type);
    }


    /**
     * @param string $type
     * @return mixed
     */
    public function getIgnoreUrl($type)
    {
        $backUrl = Mage::helper('core/url')->getCurrentBase64Url();
        return Mage_Adminhtml_Helper_Data::getUrl('adminhtml/contentsync_export/close', array('content' => $type, 'back' => $backUrl));
    }


}
