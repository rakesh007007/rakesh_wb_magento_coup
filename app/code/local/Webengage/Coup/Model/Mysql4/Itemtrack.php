<?php
class Webengage_Coup_Model_Mysql4_Itemtrack extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('webengage_coup/itemtrack', 'entity_id');
    }
}
