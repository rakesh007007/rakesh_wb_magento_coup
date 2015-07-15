<?php

class Webengage_Coup_Adminhtml_WebengagecallbackController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction() 
	{
		$this->loadLayout();
		$this->renderLayout();  
	}  
	
	public function postAction() 
	{
		Mage::helper('webengage_coup')->handleRequest($this->getRequest()->getParams()); 
		$successMessage =  Mage::helper('webengage_coup')->__('settings have been updated !!');
        Mage::getSingleton('core/session')->addSuccess($successMessage);
		$this->_redirect('adminhtml/webengagemain/index');
	}
}

