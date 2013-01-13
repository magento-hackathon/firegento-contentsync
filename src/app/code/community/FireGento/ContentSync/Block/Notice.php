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
     * @param string $code
     * @return null|string
     */
    public function getLabel($code)
    {
        return Mage::getSingleton('contentsync/notice')->getManualUpdateNoticeTypeLabel($code);
    }


    /**
     * @param string $code
     * @return null|string
     */
    public function getExportUrl($code)
    {
            return Mage::getSingleton('contentsync/notice')->getExportUrl($code);
    }

    /**
     * @return null|string Update action URL
     */
    public function getManageUrl()
    {
        return Mage::helper('adminhtml')->getUrl('adminhtml/contentsync/manage');
    }

    /**
     * @param string $code
     * @return string
     */
    public function getIgnoreUrl($code)
    {
        $backUrl = Mage::helper('core/url')->getCurrentBase64Url();
        return Mage::helper('adminhtml')->getUrl('adminhtml/contentsync/close', array('content' => $code, 'back' => $backUrl));
    }


}
