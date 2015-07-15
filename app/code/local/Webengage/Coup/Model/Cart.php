<?php
class Webengage_Coup_Model_Cart extends Mage_Core_Model_Abstract
{
    public function __construct()
    {
        $this->_init('webengage_coup/cart');
        parent::_construct();
    }
    // function which sets data for new notification in db
    public function updateRegistryData($data)
    {
        try{
            if(!empty($data))
            {

				$description= ($data['msg1']);
				$link=('Apply');
                $title='Leave intent Notification';
				$linkurl=('[[cpurl]]');
                $coupon=(implode(" ",$data['coupons']));
				$theme=($data['theme']);
				$NotificationId = Mage::helper('webengage_coup')->NotificationCreate($title,$description,$link,$linkurl,$theme);
				if($NotificationId!==null){
				    $this->setTitle($title);
                    $this->setDescription($description);
                    $this->setLink($link);
                    $this->setCoupon($coupon);
                    $this->setLinkurl($linkurl);
                    $this->setTheme($theme);
				    $this->setIdd($NotificationId);
				return 1;}
             else{
                throw new Exception("Error Processing Request: Insufficient Data Provided");
            }}
        } catch (Exception $e){
            Mage::logException($e);
			return 0;
        }
    }

}
