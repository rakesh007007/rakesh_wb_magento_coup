<?php
class Webengage_Coup_Model_Mysql4_Cart_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('webengage_coup/cart');
        parent::_construct();
    }
}
