<?php
class Webengage_Coup_Model_Mysql4_Drop_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('webengage_coup/drop');
        parent::_construct();
    }
}
