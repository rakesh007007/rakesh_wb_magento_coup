<?php
class Webengage_Coup_Model_Observer
{
    public function ItemParams($observer)
    {
        $params   = $observer->getRequest()->getParams();
        $url      = http_build_query($params);
        $items    = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
        $max      = 0;
        $lastitem = null;
        foreach ($items as $item) {
            if ($item->getId() > $max) {
                $max      = $item->getId();
                $lastitem = $item;
            }
        }
        if ($lastitem->getParentItemId() == null) {
            $itemid = $max;
        } else {
            $itemid = $lastitem->getParentItemId();
        }
        $varr = Mage::getModel('webengage_coup/itemtrack');
        $varr->updateRegistryData($itemid, $url);
        $varr->save();
    }
     public function ItemParamsAfterUpdate($observer)
    {
        $params   = $observer->getRequest()->getParams();
        $url      = http_build_query($params);
        $removedId = $params['id'];
        $varr         = Mage::getModel('webengage_coup/itemtrack')->load((int) $removedId, 'itemid');
        $superUrl     = $varr->getUrl();
        $varr->delete();
        $varr->save();
        unset($_SESSION['raku']);
        $items    = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
        $max      = 0;
        $lastitem = null;
        foreach ($items as $item) {
            if ($item->getId() > $max) {
                $max      = $item->getId();
                $lastitem = $item;
            }
        }
        if ($lastitem->getParentItemId() == null) {
            $itemid = $max;
        } else {
            $itemid = $lastitem->getParentItemId();
        }
        $varr = Mage::getModel('webengage_coup/itemtrack');
        $varr->updateRegistryData($itemid, $url);
        $varr->save();
    }
    public function Mytestmethod($observer)
    {
        $item         = $observer->getQuoteItem();
        $itemid       = $item->getItemId();
        $varr         = Mage::getModel('webengage_coup/itemtrack')->load((int) $itemid, 'itemid');
        $superUrl     = $varr->getUrl();
        $varr->delete();
        $varr->save();
        $coup = Mage::helper('webengage_coup')->couponPost();
        if(!isset($varr)){
            //nothing to do

        }
        else if (strlen($coup['code']) > 0) {
            $_SESSION['raku']       = $superUrl . '&coup=' . $coup['code'];
            $_SESSION['coupondata'] = $coup;
        } else {
            $_SESSION['raku'] = $superUrl;
        }
    }
}
?>