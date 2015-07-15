<?php
	class Webengage_Coup_Block_Adminhtml_Webengagemain extends Mage_Adminhtml_Block_Template 
	{  
		protected function _prepareLayout()
		{
		    return parent::_prepareLayout();
		}
		
		public function __construct() 
		{  
			parent::__construct();  
			$this->setFormAction(Mage::getUrl('*/*/post'));  
		}  
		
		public function handleRequest()
		{
			Mage::helper('webengage_coup')->handleRequest($this->getRequest()->getParams());
		}
	}      
?>

