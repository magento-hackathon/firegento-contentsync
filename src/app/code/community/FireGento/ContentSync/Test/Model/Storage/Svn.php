<?php
class FireGento_ContentSync_Test_Model_Storage_Svn extends EcomDev_PHPUnit_Test_Case
{
    public function testStoreData()
    {
        $model = Mage::getModel('contentsync/storage_svn');
        $this->assertNull($model->storeData(array(1, 2), 'cms_page'));

    }
}
