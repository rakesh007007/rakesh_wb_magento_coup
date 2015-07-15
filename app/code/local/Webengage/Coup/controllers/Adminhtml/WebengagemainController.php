<?php

class Webengage_Coup_Adminhtml_WebengagemainController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction() 
	{
		$this->loadLayout();
		$this->_setActiveMenu('webengage_menu_head');  
		$this->renderLayout();  
	  
	}  
	
	public function postAction() 
	{
		Mage::helper('webengage_coup')->handleRequest($this->getRequest()->getParams());
        $successMessage =  Mage::helper('webengage_coup')->__('Settings have been restored!!');
        Mage::getSingleton('core/session')->addSuccess($successMessage);
        $this->_redirect('*/*/');		
	}    
	
	protected function _isAllowed()
	{
		return Mage::getSingleton('admin/session')->isAllowed('webengage_menu_head/webengage_menu_main');
	}
}

