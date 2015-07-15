<?php
class Webengage_Coup_Model_Mysql4_Drop extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('webengage_coup/drop', 'entity_id');
    }
}
