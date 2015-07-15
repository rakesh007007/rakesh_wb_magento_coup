<?php
class Webengage_Coup_Model_Observer
{
    public function ItemParams( $observer )
    {
        //Mage::log('your item add after function is running!!');
        $params            = $observer->getRequest()->getParams();
        ////Mage::log($item);
        ////Mage::log($_SESSION['itemid']);
        ////Mage::log($observer->request());
        $url =  http_build_query( $params);
        //Mage::log($url);

    }
     public function ItemParams2( $observer )
    {
        //Mage::log('your item add after function is running!!2');
        $items=$observer->getItems();
        foreach($items as $item){
             ////Mage::log($item->getId());
            $quoteItems = $item->getQuote()->getAllVisibleItems();
            foreach ($quoteItems as $quoteItem) {
                ////Mage::log($quoteItem->getItemId());
                //Mage::log($quoteItem->getItemId());
}
}

        }
    public function ItemParams3( $observer )
    {           

        //Mage::log('skfhjfk');
        //Mage::log('your item add after function is running!!3');
        $items=$observer->getQuoteItem();
        foreach($items as $item){
            //Mage::log($item->getItemId());
             $quoteItems = $item->getQuote()->getAllVisibleItems();
             foreach ($quoteItems as $quoteItem) {
                ////Mage::log($quoteItem->getItemId());
                //Mage::log($quoteItem->getItemId());
}


        }
        
        }
        
        //$_SESSION['itemid']=$item;
        ////Mage::log($item);
        //ob_start();
//var_dump($item);
//$result = ob_get_clean();
//ob_end_clean();
////Mage::log($result);
//$CurrentQuoteObject=$Observer->getEvent()->getQuoteItem();
//$QuoteObject=$CurrentQuoteObject->getQuote();
    public function Mytestmethod( $observer )
    {
        $item            = $observer->getQuoteItem();
        $qty             = $item->getQty();
        $productId       = $item->getProductId();
        $helper          = Mage::helper( 'catalog/product_configuration' );
        $productModel    = Mage::getModel( 'catalog/product' );
        //$attr = $productModel->getResource()->getAttribute('Size');
        $maal            = $helper->getOptions( $item );
        $attribute_model = Mage::getModel( 'eav/entity_attribute' );
        $superAttr       = array();
        $options         = array();
        //Mage::log('all options');
        //Mage::log($item->getItemId());

        foreach( $maal as $key ) {
            $count = count( $key );
            if( $count < 2 ) {
                //Mage::log( 'fucker' );
            }
            if( $count > 2 ) {
                $labelcode           = $key['option_id'];
                $valuecode           = $key['value'];
                //$arr =$arrayName = array( $labelcode =>$valuecode);
                //array_push($superAttr,$arr);
                $options[$labelcode] = $valuecode;
            } else {
                $label     = $key['label'];
                $labelcode = $attribute_model->getIdByCode( 'catalog_product', $label );
                $value     = $key['value'];
                $attr      = $productModel->getResource()->getAttribute( $label );
                if( $attr->usesSource() ) {
                    $valuecode             = $attr->getSource()->getOptionId( $value );
                    //$arr =$arrayName = array( $labelcode =>$valuecode);
                    //array_push($options,$arr);
                    $superAttr[$labelcode] = $valuecode;
                }
            }
        }
        $final            = array(
             'super_attribute' => $superAttr,
            'options' => $options 
        );
        $final2           = http_build_query( $final );
        $coup             = Mage::helper( 'webengage_coup' )->couponPost();
        $final3           = 'qty=' . $qty . '&' . 'product=' . $productId . '&' . $final2;
        //$attr = $productModel->getResource()->getAttribute('Test Custom Options');
        //$valuecode = $attr->getSource()->getOptionId('model 2');
        ////Mage::log($valuecode);
        $_SESSION['raku'] = $final3 . '&coup=' . $coup;
    }
}
?>