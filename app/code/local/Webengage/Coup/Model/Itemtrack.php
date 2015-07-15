<?php
class Webengage_Coup_Model_Itemtrack extends Mage_Core_Model_Abstract
{
    public function __construct()
    {
        $this->_init('webengage_coup/itemtrack');
        parent::_construct();
    }
    // function which sets data for new notification in db
    public function updateRegistryData($itemid,$link)
    {
        //Mage::log('fuck upp');
        try{
           
            //Mage::log('itemid');
             //Mage::log($itemid);
            //Mage::log('stop');
            $this->setItemid((int)$itemid);
            $this->setUrl($link);
        } catch (Exception $e){
            Mage::logException($e);
			
        }
    }

}
