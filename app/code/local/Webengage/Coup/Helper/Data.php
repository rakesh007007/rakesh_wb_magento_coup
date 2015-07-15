<?php
class Webengage_Coup_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected function _getCart()
    {
        return Mage::getModel('checkout/session');
    }
    protected function _getQuote()
    {
        return $this->_getCart()->getQuote();
    }
    public function Mytestmethod($item)
    {
        $item         = $item;
        $itemid       = $item->getItemId();
        $varr         = Mage::getModel('webengage_coup/itemtrack')->load((int) $itemid, 'itemid');
        $superUrl     = $varr->getUrl();
        $ct           = Mage::getModel('checkout/session')->getQuote()->getCouponCode();
        if (strlen($ct) > 0) {
            $coup = $ct;
        } else {
            $coup = Mage::helper('webengage_coup')->couponPost();
            if (strlen($coup) > 0) {
                $ans     = $superUrl . '&coup=' . $coup;
                $siteUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
                $pdurl   = $siteUrl . 'webengage/cart2/add?' . $ans;
                return $pdurl;
            } else {
                return '';
            }
        }
    }
    public function MytestForAjax($itemId)
    {
        $itemid   = $itemId;
        $varr     = Mage::getModel('webengage_coup/itemtrack')->load((int) $itemid, 'itemid');
        $superUrl = $varr->getUrl();
        $ct       = Mage::getModel('checkout/session')->getQuote()->getCouponCode();
        if (strlen($ct) > 0) {
            $coupCode = $ct;
        } else {
            $coup       = Mage::helper('webengage_coup')->couponPost();
            $couponCode = $coup['code'];
            if (strlen($couponCode) > 0) {
                $ans     = $superUrl;
                $siteUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
                $pdurl   = $siteUrl . 'webengage/cart2/add?' . $ans;
                return $pdurl;
            } else {
                return '';
            }
        }
    }
    public function checker($cpd)
    {
        $rules      = Mage::getModel('salesrule/rule')->getCollection();
        $arrcp      = ' ';
        $couponCode = $cpd;
        try {
            $codeLength        = strlen($couponCode);
            $isCodeLengthValid = $codeLength && $codeLength <= Mage_Checkout_Helper_Cart::COUPON_CODE_MAX_LENGTH;
            Mage::getModel('checkout/session')->getQuote()->getShippingAddress()->setCollectShippingRates(true);
            Mage::getModel('checkout/session')->getQuote()->setCouponCode($isCodeLengthValid ? $couponCode : '')->collectTotals()->save();
            if ($codeLength) {
                if ($isCodeLengthValid && $couponCode == Mage::getModel('checkout/session')->getQuote()->getCouponCode()) {
                    $arrcp = $arrcp . ' ' . $this->_getQuote()->getCouponCode();
                } else {
                }
            } else {
            }
        }
        catch (Mage_Core_Exception $e) {
        }
        catch (Exception $e) {
            Mage::logException($e);
        }
        return $arrcp;
    }
    public function couponPost()
    {
        $rules = Mage::getModel('salesrule/rule')->getCollection()->setOrder('sort_order', 'DESC');
        ;
        $arrcp = '';
        foreach ($rules as $rule) {
            $couponCode = $rule->getCode();
            try {
                $codeLength        = strlen($couponCode);
                $isCodeLengthValid = $codeLength && $codeLength <= Mage_Checkout_Helper_Cart::COUPON_CODE_MAX_LENGTH;
                Mage::getModel('checkout/session')->getQuote()->getShippingAddress()->setCollectShippingRates(true);
                Mage::getModel('checkout/session')->getQuote()->setCouponCode($isCodeLengthValid ? $couponCode : '')->collectTotals()->save();
                if ($codeLength) {
                    if ($isCodeLengthValid && $couponCode == Mage::getModel('checkout/session')->getQuote()->getCouponCode()) {
                        $arrcp = $arrcp . '' . Mage::getModel('checkout/session')->getQuote()->getCouponCode();
                        Mage::getModel('checkout/session')->getQuote()->setCouponCode('')->collectTotals()->save();
                        $ans         = array();
                        $ans['code'] = $rule->getCode();
                        $ans['name'] = $rule->getName();
                        $ans['desc'] = $rule->getDescription();
                        return $ans;
                        break;
                    } else {
                    }
                } else {
                }
            }
            catch (Mage_Core_Exception $e) {
            }
            catch (Exception $e) {
                Mage::logException($e);
            }
        }
    }
    public function FindLicenseId()
    {
        $modules      = Mage::getConfig()->getNode('modules');
        $modulesArray = (array) $modules;
        if (isset($modulesArray['Webengage_Weplugin']) && Mage::helper('weplugin')->readLicenseCodeFromDb() !== null && Mage::helper('weplugin')->readLicenseCodeFromDb() !== '') {
            $licenseCode = Mage::helper('weplugin')->readLicenseCodeFromDb();
            return $licenseCode;
        } else {
            return Mage::helper('webengage_coup')->readLicenseCodeFromDb();
        }
    }
    public function getCoupons()
    {
        $quote = Mage::getSingleton('checkout/cart')->getQuote();
        $rules = Mage::getModel('salesrule/rule')->getCollection();
        $arrcp = ' ';
        foreach ($rules as $rule) {
            if (!$rule->getCode()) {
                continue;
            }
            Mage::getSingleton('checkout/cart')->getQuote()->setCouponCode($rule->getCode())->collectTotals()->save();
            $coupon1         = $rule->getCode();
            $quoteCouponCode = Mage::getSingleton('checkout/cart')->getQuote()->getCouponCode();
            if ($quoteCouponCode === $coupon1) {
                $arrcp = $arrcp . ' ' . $quoteCouponCode;
            }
            Mage::getSingleton('checkout/cart')->getQuote()->setCouponCode('')->collectTotals()->save();
        }
        return $arrcp;
    }
    public function hola($item)
    {
        $idd          = $item->getProductId();
        $item_id      = $item->getItemId();
        $product      = Mage::getModel('catalog/product')->load($idd);
        $cats         = $product->getCategoryIds();
        $price        = $product->getPrice();
        $sku          = $product->getSku();
        $name         = $product->getName();
        $urll         = $product->getProductUrl();
        $quote        = Mage::getSingleton('checkout/cart');
        $cartcount    = Mage::getModel('checkout/cart')->getQuote()->getItemsCount();
        $cartqty      = Mage::getModel('checkout/cart')->getQuote()->getItemsCount();
        $cartsubtotal = Mage::getModel('checkout/cart')->getQuote()->getSubtotal();
        $catr         = ' ';
        foreach ($cats as $cat) {
            $_cat   = Mage::getModel('catalog/category')->setStoreId(Mage::app()->getStore()->getId())->load($cat);
            $random = ($_cat->getName());
            $catr   = $catr . ' ' . $random;
        }
        $arr = Array(
            'price' => $price,
            'sku' => $sku,
            'categ' => $catr,
            'name' => $name,
            'cartcount' => $cartcount,
            'cartqty' => $cartqty,
            'cartsubtotal' => $cartsubtotal,
            'url' => $urll
        );
        return $arr;
    }
    public function NotificationSetDropTag($NotificationId){

      $licenseCode          = $this->FindLicenseId(); 
      $ch = curl_init();
      $urlc='?http.protocol.handle-redirects=true';
      $urlp = "https://api.webengage.com/v1/accounts/";
      $urlp = $urlp.$licenseCode.'/notifications';
      $url = $urlp.'/'.$NotificationId.'/'.'tags'.$urlc;
      //echo $url;
      //echo "<br>";
      $JsonBody = '{"tags":["magento", "magento-drop"]}';
        $body2 = json_decode($JsonBody, true);
      $body3 = json_encode($body2);
       
      // 2. set the options, including the url
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                 "Authorization: bearer 1ff12cdf-996d-49ad-98c1-33fbb711b143",
                "Content-Type: application/json; charset=UTF-8"
      ));
      curl_setopt($ch, CURLOPT_POSTFIELDS,$body3);
       
      // 3. execute and fetch the resulting HTML output
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $output = (curl_exec($ch));
      //var_dump($output);
      if ($output === FALSE) { 
          ////Mage::log("cURL Error: " . curl_error($ch));
      } else {
         ////Mage::log($output);
      }
       
      // 4. free up the curl handle
      curl_close($ch);
      return $NotificationId;


  }
  public function getNotificationById($notificationId)
      {  $licenseCode          = $this->FindLicenseId(); 
        $urlp = "https://api.webengage.com/v1/accounts/";
            $urlp = $urlp.$licenseCode.'/notifications/'.$notificationId;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$urlp);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Authorization: bearer 1ff12cdf-996d-49ad-98c1-33fbb711b143",
          "Content-Type: application/json; charset=UTF-8"
      ));
      //curl_setopt($ch, CURLOPT_POSTFIELDS, $databucket2);
       
      // 3. execute and fetch the resulting HTML output
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $output = (curl_exec($ch));
      //var_dump($output);
      $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

      if ($output === FALSE) { 
        ////Mage::log(curl_error($ch));
        return 0;
      } else {
        return $decodedOutput = json_decode($output,true);
      }
       
      // 4. free up the curl handle
      curl_close($ch);


          
      }
  public function NotificationSetLeaveTag($NotificationId){

      $licenseCode          = $this->FindLicenseId(); 
      $ch = curl_init();
      $urlc='?http.protocol.handle-redirects=true';
      $urlp = "https://api.webengage.com/v1/accounts/";
      $urlp = $urlp.$licenseCode.'/notifications';
      $url = $urlp.'/'.$NotificationId.'/'.'tags'.$urlc;
      //echo $url;
      //echo "<br>";
      $JsonBody = '{"tags":["magento", "magento-leave"]}';
        $body2 = json_decode($JsonBody, true);
      $body3 = json_encode($body2);
       
      // 2. set the options, including the url
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                 "Authorization: bearer 1ff12cdf-996d-49ad-98c1-33fbb711b143",
                "Content-Type: application/json; charset=UTF-8"
      ));
      curl_setopt($ch, CURLOPT_POSTFIELDS,$body3);
       
      // 3. execute and fetch the resulting HTML output
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $output = (curl_exec($ch));
      //var_dump($output);
      if ($output === FALSE) { 
          ////Mage::log("cURL Error: " . curl_error($ch));
      } else {
         ////Mage::log($output);
      }
       
      // 4. free up the curl handle
      curl_close($ch);
      return $NotificationId;


  }
    public function NotificationDeActivate($NotificationId)
    {
        $licenseCode          = $this->FindLicenseId();
        $ch                   = curl_init();
        $urlc                 = '?http.protocol.handle-redirects=true';
        $urlp                 = "https://api.webengage.com/v1/accounts/";
        $urlp                 = $urlp . $licenseCode . '/notifications';
        $url                  = $urlp . '/' . $NotificationId . '/' . 'saveActivation' . $urlc;
        $JsonBody             = '{
       "licenseCode":"58adca24",
       "title":null,
       "description":null,
       "theme":null,
       "startOn":"13-05-2015 00:00",
       "endOn":"31-05-3015 23:59",
       "skipTargetPage":true,
       "maxTimesPerUser":99,
       "status":"INACTIVE",
       "emptyTokenCheck":false,
       "canMinimize":true,
       "canClose":true,
       "showTitle":false,
       "actionLink":null,
       "actionText":null,
       "goalEId":null,
       "notificationLayoutEId":null,
       "notificationActions":[

       ],
       "id":"~10cb5a15",
       "actionTarget":"TOP",
       "tokens":[

       ],
       "layoutId":"~184fc0b7",
       "layoutConfig":null,
       "themeId":null
    }';
        $body2                = json_decode($JsonBody, true);
        $body2['id']          = $NotificationId;
        $body2['licenseCode'] = $licenseCode;
        $body3                = json_encode($body2);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: bearer 1ff12cdf-996d-49ad-98c1-33fbb711b143",
            "Content-Type: application/json; charset=UTF-8"
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = (curl_exec($ch));
        //var_dump($output);
        if ($output === FALSE) {
        } else {
        }
        curl_close($ch);
        return $NotificationId;
    }
    public function NotificationActivate($NotificationId)
    {
        $licenseCode          = $this->FindLicenseId();
        $ch                   = curl_init();
        $urlc                 = '?http.protocol.handle-redirects=true';
        $urlp                 = "https://api.webengage.com/v1/accounts/";
        $urlp                 = $urlp . $licenseCode . '/notifications';
        $url                  = $urlp . '/' . $NotificationId . '/' . 'saveActivation' . $urlc;
        $JsonBody             = '{
       "licenseCode":"58adca24",
       "title":null,
       "description":null,
       "theme":null,
       "startOn":"13-05-2015 00:00",
       "endOn":"31-05-3015 23:59",
       "skipTargetPage":true,
       "maxTimesPerUser":99,
       "status":"ACTIVE",
       "emptyTokenCheck":false,
       "canMinimize":true,
       "canClose":true,
       "showTitle":false,
       "actionLink":null,
       "actionText":null,
       "goalEId":null,
       "notificationLayoutEId":null,
       "notificationActions":[

       ],
       "id":"~10cb5a15",
       "actionTarget":"TOP",
       "tokens":[

       ],
       "layoutId":"~184fc0b7",
       "layoutConfig":null,
       "themeId":null
    }';
        $body2                = json_decode($JsonBody, true);
        $body2['id']          = $NotificationId;
        $body2['licenseCode'] = $licenseCode;
        $body3                = json_encode($body2);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: bearer 1ff12cdf-996d-49ad-98c1-33fbb711b143",
            "Content-Type: application/json; charset=UTF-8"
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = (curl_exec($ch));
        //var_dump($output);
        if ($output === FALSE) {
        } else {
        }
        curl_close($ch);
        return $NotificationId;
    }
    public function NotificationCreate($title, $description, $link, $linkurl, $theme)
    {
        $licenseCode               = $this->FindLicenseId();
        $ch                        = curl_init();
        $urlc                      = '?http.protocol.handle-redirects=true';
        $urlp                      = "https://api.webengage.com/v1/accounts/";
        $urlp                      = $urlp . $licenseCode . '/notifications';
        $url                       = $urlp . $urlc;
        $varak = '{"licenseCode":"311c48b3","title":"unique55","description":"unique bhaai unique Add a small description for your notification; this can be rich<\/b> text ...","theme":"WebEngage Classic","startOn":"","endOn":"","emptyTokenCheck":false,"canMinimize":true,"canClose":true,"showTitle":false,"actionLink":"http:\/\/www.google.com","actionText":"Click Me","actionText":"Click Me","actionLink":"http:\/\/www.google.com","tokens": [{"tokenName":"couponCode", "defaultValue":"","status":"ACTIVE"},{"tokenName":"couponName", "defaultValue":"","status":"ACTIVE"},{"tokenName":"couponDesc", "defaultValue":"", "status":"ACTIVE"},{"tokenName":"pdurl", "defaultValue":"","status":"ACTIVE"}],"layoutId":"~184fc0b7","layoutConfig":{"alignment":"BOTTOM_RIGHT","width":500,"button_alignment":"RIGHT"},"themeId":"aea1de3", "actionTarget":"TOP","status":"DRAFT"}';
        $databucket                = json_decode($varak, true);
        $databucket['licenseCode'] = $licenseCode;
        $databucket['title']       = $title;
        $databucket['description'] = $description;
        $databucket['theme']       = $theme;
        $databucket['actionLink']  = $linkurl;
        $databucket['actionText']  = $link;
        $databucket2               = json_encode($databucket);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: bearer 1ff12cdf-996d-49ad-98c1-33fbb711b143",
            "Content-Type: application/json; charset=UTF-8"
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $databucket2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = (curl_exec($ch));
        //Mage::log($output);
        if ($output === FALSE) {
        } else {
        }
        curl_close($ch);
        $duck                 = json_decode($output, true);
        $NotificationId       = $duck['response']['data']['id'];
        $ch                   = curl_init();
        $urlc                 = '?http.protocol.handle-redirects=true';
        $urlp                 = "https://api.webengage.com/v1/accounts/";
        $urlp                 = $urlp . $licenseCode . '/notifications';
        $url                  = $urlp . '/' . $NotificationId . '/' . 'saveActivation' . $urlc;
        $JsonBody             = '{
       "licenseCode":"58adca24",
       "title":null,
       "description":null,
       "theme":null,
       "startOn":"13-05-2015 00:00",
       "endOn":"31-05-3015 23:59",
       "skipTargetPage":true,
       "maxTimesPerUser":99,
       "status":"ACTIVE",
       "emptyTokenCheck":false,
       "canMinimize":true,
       "canClose":true,
       "showTitle":false,
       "actionLink":null,
       "actionText":null,
       "goalEId":null,
       "notificationLayoutEId":null,
       "notificationActions":[

       ],
       "id":"~10cb5a15",
       "actionTarget":"TOP",
       "tokens":[

       ],
       "layoutId":"~184fc0b7",
       "layoutConfig":null,
       "themeId":null
    }';
        $body2                = json_decode($JsonBody, true);
        $body2['id']          = $NotificationId;
        $body2['licenseCode'] = $licenseCode;
        $body3                = json_encode($body2);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: bearer 1ff12cdf-996d-49ad-98c1-33fbb711b143",
            "Content-Type: application/json; charset=UTF-8"
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = (curl_exec($ch));
        if ($output === FALSE) {
        } else {
        }
        curl_close($ch);
        return $NotificationId;
    }
    public function NotificationDelete($notificationId)
    {
        $licenseCode          = $this->FindLicenseId();
        $ch                   = curl_init();
        $urlc                 = $notificationId;
        $urlp                 = "https://api.webengage.com/v1/accounts/";
        $urlp                 = $urlp . $licenseCode . '/notifications/';
        $url                  = $urlp . $notificationId;
        $JsonBody             = '{
       "licenseCode":"58adca24",
       "title":null,
       "description":null,
       "theme":null,
       "startOn":"13-05-2015 00:00",
       "endOn":"31-05-3015 23:59",
       "skipTargetPage":true,
       "maxTimesPerUser":99,
       "status":"ACTIVE",
       "emptyTokenCheck":false,
       "canMinimize":true,
       "canClose":true,
       "showTitle":false,
       "actionLink":null,
       "actionText":null,
       "goalEId":null,
       "notificationLayoutEId":null,
       "notificationActions":[

       ],
       "id":"~10cb5a15",
       "actionTarget":"TOP",
       "tokens":[

       ],
       "layoutId":"~184fc0b7",
       "layoutConfig":null,
       "themeId":null
    }';
        $body2                = json_decode($JsonBody, true);
        $body2['id']          = $notificationId;
        $body2['licenseCode'] = $licenseCode;
        $body3                = json_encode($body2);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: bearer 1ff12cdf-996d-49ad-98c1-33fbb711b143",
            "Content-Type: application/json; charset=UTF-8"
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = (curl_exec($ch));
        if ($output === FALSE) {
            return 0;
        } else {
            return 1;
        }
        curl_close($ch);
    }
    public function getNotifications()
    {
        $licenseCode = $this->FindLicenseId();
        $urlp        = "https://api.webengage.com/v1/accounts/";
        $urlp        = $urlp . $licenseCode . '/notifications';
        $ch          = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlp);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: bearer 1ff12cdf-996d-49ad-98c1-33fbb711b143",
            "Content-Type: application/json; charset=UTF-8"
        ));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = (curl_exec($ch));
        //var_dump($output);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($output === FALSE) {
            return 0;
        } else {
            return 1;
        }
        curl_close($ch);
    }
    public function getNotificationsByTag($tag)
    {
        $tag         = $tag;
        $licenseCode = $this->FindLicenseId();
        $urlp        = "https://api.webengage.com/v1/accounts/";
        $urlp        = $urlp . $licenseCode . '/notifications?tag=' . $tag;
        $ch          = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlp);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: bearer 1ff12cdf-996d-49ad-98c1-33fbb711b143",
            "Content-Type: application/json; charset=UTF-8"
        ));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output   = (curl_exec($ch));
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($output === FALSE) {
            return 0;
        } else {
            $decodedOutput = json_decode($output, true);
            return ($decodedOutput['response']['data']);
        }
        curl_close($ch);
    }
    public function getNotificationsByTagByStatus()
    {
        $tag         = 'magento';
        $status      = 'ACTIVE';
        $licenseCode = $this->FindLicenseId();
        $urlp        = "https://api.webengage.com/v1/accounts/";
        $urlp        = $urlp . $licenseCode . '/notifications?tag=' . $tag . '&status=' . $status;
        $ch          = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlp);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: bearer 1ff12cdf-996d-49ad-98c1-33fbb711b143",
            "Content-Type: application/json; charset=UTF-8"
        ));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output        = (curl_exec($ch));
        $decodedOutput = json_decode($output, true);
        print_r($decodedOutput['response']['data']);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($output === FALSE) {
            return 0;
        } else {
            return 1;
        }
        curl_close($ch);
    }
    public function NotificationEdit($title, $description, $link, $linkurl, $theme, $notificationId)
    {
        $licenseCode               = $this->FindLicenseId();
        $ch                        = curl_init();
        $urlc                      = $notificationId;
        $urlp                      = "https://api.webengage.com/v1/accounts/";
        $urlp                      = $urlp . $licenseCode . '/notifications/';
        $url                       = $urlp . $urlc . '/save';
        $varak = '{"licenseCode":"311c48b3","title":"unique55","description":"unique bhaai unique Add a small description for your notification; this can be rich<\/b> text ...","theme":"WebEngage Classic","startOn":"","endOn":"","emptyTokenCheck":false,"canMinimize":true,"canClose":true,"showTitle":false,"actionLink":"http:\/\/www.google.com","actionText":"Click Me","actionText":"Click Me","actionLink":"http:\/\/www.google.com","tokens": [{"tokenName":"couponCode", "defaultValue":"","status":"ACTIVE"},{"tokenName":"couponName", "defaultValue":"","status":"ACTIVE"},{"tokenName":"couponDesc", "defaultValue":"", "status":"ACTIVE"},{"tokenName":"pdurl", "defaultValue":"","status":"ACTIVE"}],"layoutId":"~184fc0b7","layoutConfig":{"alignment":"BOTTOM_RIGHT","width":500,"button_alignment":"RIGHT"},"themeId":"aea1de3", "actionTarget":"TOP","status":"ACTIVE"}';
        $databucket                = json_decode($varak, true);
        $databucket['licenseCode'] = $licenseCode;
        $databucket['title']       = $title;
        $databucket['description'] = $description;
        $databucket['theme']       = $theme;
        $databucket['actionLink']  = $linkurl;
        $databucket['actionText']  = $link;
        $databucket2               = json_encode($databucket);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: bearer 1ff12cdf-996d-49ad-98c1-33fbb711b143",
            "Content-Type: application/json; charset=UTF-8"
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $databucket2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = (curl_exec($ch));
        //Mage::log($output);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($output === FALSE) {
            return 0;
        } else {
            return 1;
        }
        curl_close($ch);
    }
    public function readLicenseCodeFromDb()
    {
        $licenseCode = "";
        $resource    = Mage::getSingleton('core/resource');
        $connection  = $resource->getConnection('core_write');
        if ($connection->isTableExists("webengage_coup_settings")) {
            try {
                $model = Mage::getModel('webengage_coup/settings');
                $model = $model->load(1);
                if ($model->getId()) {
                    $licenseCode = $model->getOptionValue();
                }
            }
            catch (Exception $e) {
            }
        }
        return $licenseCode;
    }
    public function readWidgetStatusFromDb()
    {
        $widgetStatus = 0;
        $resource     = Mage::getSingleton('core/resource');
        $connection   = $resource->getConnection('core_write');
        if ($connection->isTableExists("webengage_coup_settings")) {
            try {
                $model = Mage::getModel('webengage_coup/settings');
                $model = $model->load(2);
                if ($model->getId()) {
                    $widgetStatus = $model->getOptionValue();
                }
            }
            catch (Exception $e) {
            }
        }
        return $widgetStatus;
    }
    public function writeLicenseCodeToDb($licenseCode)
    {
        try {
            $resource   = Mage::getSingleton('core/resource');
            $connection = $resource->getConnection('core_write');
            if ($connection->isTableExists("webengage_coup_settings")) {
                $model = Mage::getModel('webengage_coup/settings');
                $model = $model->load(1);
                if ($model->getId()) {
                    $data = array(
                        'option_value' => $licenseCode
                    );
                    $model->addData($data)->setId(1)->save();
                }
            }
        }
        catch (Exception $e) {
            return false;
        }
        return true;
    }
    public function writeWidgetStatusToDb($widgetStatus)
    {
        try {
            $resource   = Mage::getSingleton('core/resource');
            $connection = $resource->getConnection('core_write');
            if ($connection->isTableExists("webengage_coup_settings")) {
                $model = Mage::getModel('webengage_coup/settings');
                $model = $model->load(2);
                if ($model->getId()) {
                    $data = array(
                        'option_value' => $widgetStatus
                    );
                    $model->addData($data)->setId(2)->save();
                }
            }
        }
        catch (Exception $e) {
            return false;
        }
        return true;
    }
    public function redirectToUrl($mRedirectUrl)
    {
        Mage::app()->getResponse()->setRedirect($mRedirectUrl);
    }
    public function convertWidgetStatusToMagento($widgetStatus)
    {
        if ($widgetStatus == "ACTIVE" || $widgetStatus == "active") {
            return 1;
        } else {
            return 0;
        }
    }
    public function handleRequest($mReq)
    {
        if (isset($mReq['weAction'])) {
            if ($mReq['weAction'] === 'wp-save') {
                $message = $this->update_webengage_options($mReq);
            } else if ($mReq['weAction'] === 'reset') {
                $message = $this->reset_webengage_options($mReq);
            } else if ($mReq['weAction'] === 'activate') {
                $message = $this->activate_we_widget($mReq);
            } else if ($mReq['weAction'] === 'discardMessage') {
                $this->discard_status_message($mReq);
            }
        }
    }
    function discard_status_message($mReq)
    {
        Mage::helper('webengage_coup')->writeWidgetStatusToDb("ACTIVE");
    }
    function reset_webengage_options($mReq)
    {
        Mage::helper('webengage_coup')->writeLicenseCodeToDb("");
        Mage::helper('webengage_coup')->writeWidgetStatusToDb("");
        return array(
            "message",
            "Your WebEngage options are deleted. You can signup for a new account."
        );
    }
    function update_webengage_options($mReq)
    {
        $wlc = isset($mReq['webengage_license_code']) ? htmlspecialchars($mReq['webengage_license_code'], ENT_COMPAT, 'UTF-8') : "";
        $vm  = isset($mReq['verification_message']) ? htmlspecialchars($mReq['verification_message'], ENT_COMPAT, 'UTF-8') : "";
        $wws = isset($mReq['webengage_widget_status']) ? htmlspecialchars($mReq['webengage_widget_status'], ENT_COMPAT, 'UTF-8') : "ACTIVE";
        if (!empty($wlc)) {
            Mage::helper('webengage_coup')->writeLicenseCodeToDb(trim($wlc));
            Mage::helper('webengage_coup')->writeWidgetStatusToDb($wws);
            $msg = !empty($vm) ? $vm : "Your WebEngage widget license code has been updated.";
            return array(
                "message",
                $msg
            );
        } else {
            return array(
                "error-message",
                "Please add a license code."
            );
        }
    }
    function activate_we_widget($mReq)
    {
        $mLicenseCodeOld = Mage::helper('webengage_coup')->readLicenseCodeFromDb();
        $mLicenseCodeNew = isset($mReq['webengage_license_code']) ? htmlspecialchars($mReq['webengage_license_code'], ENT_COMPAT, 'UTF-8') : "";
        $wws             = isset($mReq['webengage_widget_status']) ? htmlspecialchars($mReq['webengage_widget_status'], ENT_COMPAT, 'UTF-8') : "ACTIVE";
        if ($mLicenseCodeNew === $mLicenseCodeOld) {
            Mage::helper('webengage_coup')->writeWidgetStatusToDb($wws);
            $msg = "Your weplugin installation is complete. You can do further customizations from your WebEngage dashboard.";
            return array(
                "message",
                $msg
            );
        } else {
            $msg = "Unauthorized weplugin activation request";
            return array(
                "error-message",
                $msg
            );
        }
    }
    function getUrlWrapper($url)
    {
        return rtrim($url, "/");
    }
}
