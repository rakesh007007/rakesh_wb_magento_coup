<?php
class Webengage_Coup_Model_Settings extends Mage_Core_Model_Abstract
{
     public function _construct()
     {
         parent::_construct();
         $this->_init('webengage_coup/settings');
     }
     
     /*
    protected function _beforeSave()
    {
	parent::_beforeSave();
        if($this->getResource()->checkDuplicate($this))
        {
           throw new Exception('User Email Already Exists');
        }
	//$this->getResource()   returns the object of the resource model, where can put in the sql operations
        return $this;
    }
    */
}

