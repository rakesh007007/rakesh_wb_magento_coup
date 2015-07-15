<?php

/**
 * Wishlist front controller
 *
 * @category    Codephun
 * @package     Codephun_Wishlist
 * @author      Bjarne Oeverli <my@email.com>
 */
require_once Mage::getModuleDir('controllers', 'Mage_Checkout') . DS . 'CartController.php';
class Webengage_Coup_CartController extends Mage_Checkout_CartController
{
    public function addcouponAction()
    {
        /**
         * No reason continue with empty shopping cart
         */
        if (!$this->_getCart()->getQuote()->getItemsCount()) {
            //Mage::log('case1');
        }

        $couponCode = (string) $this->getRequest()->getParam('coup');
        if ($this->getRequest()->getParam('remove') == 1) {
            //Mage::log('remove request');
            $couponCode = '';
        }
        $oldCouponCode = $this->_getQuote()->getCouponCode();
        //Mage::log('frrom coupon controller00');
            //Mage::log($this->_getQuote()->getCouponCode());

        if (!strlen($couponCode) && !strlen($oldCouponCode)) {
            //Mage::log('case2');
        }

        try {
            $codeLength = strlen($couponCode);
            $isCodeLengthValid = $codeLength && $codeLength <= Mage_Checkout_Helper_Cart::COUPON_CODE_MAX_LENGTH;

            $this->_getQuote()->getShippingAddress()->setCollectShippingRates(true);
            $this->_getQuote()->setCouponCode($isCodeLengthValid ? $couponCode : '')
                ->collectTotals()
                ->save();
            //Mage::log('frrom coupon controller');
            //Mage::log($this->_getQuote()->getCouponCode());


            if ($codeLength) {
                if ($isCodeLengthValid && $couponCode == $this->_getQuote()->getCouponCode()) {
                    $this->_getSession()->addSuccess(
                        $this->__('Coupon code "%s" was applied.', Mage::helper('core')->escapeHtml($couponCode))
                    );
                } else {
                    $this->_getSession()->addError(
                        $this->__('Coupon code "%s" is not valid.', Mage::helper('core')->escapeHtml($couponCode))
                    );
                }
            } else {
                $this->_getSession()->addSuccess($this->__('Coupon code was canceled.'));
            }
            $this->indexAction();

        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addError($this->__('Cannot apply the coupon code.'));
            Mage::logException($e);
        }
    }
    public function indexAction()
    {
		 $rakesh =$this->getRequest()->getParam('rakesh');
		 if($rakesh==true){
		 echo "<script type=\"text/javascript\" id=\"_webengage_script_tag\">
		 function precoup(price1,categ1,name1,sku1,cartcount1,cartqty1,cartsubtotal1,licenseCode,url,coupons){
				coup(price1,categ1,name1,sku1,cartcount1,cartqty1,cartsubtotal1,licenseCode,url,coupons);
			}
	     function coup( price1,categ1,name1,sku1,cartcount1,cartqty1,cartsubtotal1,licenseCode,url,coupons){
					   window._weq = window._weq || {};
					  _weq['webengage.licenseCode'] = licenseCode;
					  _weq['webengage.survey.forcedRender'] = true;
					  _weq['webengage.notification.forcedRender'] = true;
					  _weq['webengage.ruleData'] = {'cartDropped': 'yes','price': price1, 'categ':categ1,'coupons':coupons,'name':name1,'sku':sku1,'cartcount':cartcount1,'cartqty':cartqty1,'cartsubtotal':cartsubtotal1};
					  _weq['webengage.widgetVersion'] = '4.0';
					  _weq['webengage.notification.tokens'] = {'productUrl':url};
					  
					  
					   (function(d){ 
						var _we = d.createElement('script');
						_we.type = 'text/javascript';
						_we.async = true;
						_we.src = (d.location.protocol == 'https:' ? '//ssl.widgets.webengage.com' : '//cdn.widgets.webengage.com') +  '/js/widget/webengage-min-v-4.0.js';
						var _sNode = d.getElementById('_webengage_script_tag');
						_sNode.parentNode.insertBefore(_we, _sNode);
					  })(document);
					  }
				</script>";
			 $price1 = $this->getRequest()->getParam('price');
		 $categ1 = $this->getRequest()->getParam('categ');
		 $name1 = $this->getRequest()->getParam('name');
		 $sku1 = $this->getRequest()->getParam('sku');
		 $url = $this->getRequest()->getParam('url');
		 $cartcount = $this->getRequest()->getParam('cartcount');
		 $cartqty = $this->getRequest()->getParam('cartqty');
		 $cartsubtotal = $this->getRequest()->getParam('cartsubtotal');
		 $coupons = $this->getRequest()->getParam('coupons');
		 $licenseCode = $this->getRequest()->getParam('licenseCode');
		 $str2 = "<script>"."precoup"."(".($price1).",".json_encode($categ1).",".json_encode($name1).",".json_encode($sku1).",".($cartcount).",".($cartqty).",".($cartsubtotal).",".json_encode($licenseCode).",".json_encode($url).",".json_encode($coupons).")</script>";
		 echo $str2;
			 
			 
			
		 }
        $cart = $this->_getCart();
        if ($cart->getQuote()->getItemsCount()) {
            $cart->init(); 
            $cart->save();

            if (!$this->_getQuote()->validateMinimumAmount()) {
                $minimumAmount = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())
                    ->toCurrency(Mage::getStoreConfig('sales/minimum_order/amount'));

                $warning = Mage::getStoreConfig('sales/minimum_order/description')
                    ? Mage::getStoreConfig('sales/minimum_order/description')
                    : Mage::helper('checkout')->__('Minimum order amount is %s', $minimumAmount);

                $cart->getCheckoutSession()->addNotice($warning);
            }
        }

        // Compose array of messages to add
        $messages = array();
        foreach ($cart->getQuote()->getMessages() as $message) {
            if ($message) {
                // Escape HTML entities in quote message to prevent XSS
                $message->setCode(Mage::helper('core')->escapeHtml($message->getCode()));
                $messages[] = $message;
            }
        }
        $cart->getCheckoutSession()->addUniqueMessages($messages);

        /**
         * if customer enteres shopping cart we should mark quote
         * as modified bc he can has checkout page in another window.
         */
        $this->_getSession()->setCartWasUpdated(true);

        Varien_Profiler::start(__METHOD__ . 'cart_display');
        $this
            ->loadLayout()
            ->_initLayoutMessages('checkout/session')
            ->_initLayoutMessages('catalog/session')
            ->getLayout()->getBlock('head')->setTitle($this->__('Shopping Cart'));
        $this->renderLayout();
        Varien_Profiler::stop(__METHOD__ . 'cart_display');
    }
	 
}