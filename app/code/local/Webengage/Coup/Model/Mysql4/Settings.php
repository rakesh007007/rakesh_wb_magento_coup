<?php
class Webengage_Coup_Model_Mysql4_Settings extends Mage_Core_Model_Mysql4_Abstract
{
     public function _construct()
     {
         $this->_init('webengage_coup/settings', 'id');
     }
}

