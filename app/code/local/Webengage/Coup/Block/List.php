<?php
class Webengage_Coup_Block_list extends Mage_Core_Block_Template
{
    public function getCustomerRegistries()
    {
        $collection = null;
        $currentCustomer = Mage::getSingleton('customer/session')->getCustomer();
        if($currentCustomer)
        {
            $collection = Mage::getModel('webengage_coup/cart')->getCollection();

        }
        return $collection;
    }
}