<?php
class Webengage_Coup_Model_Mysql4_Settings_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
 {
     public function _construct()
     {
         parent::_construct();
         $this->_init('webengage_coup/settings');
     }
}

