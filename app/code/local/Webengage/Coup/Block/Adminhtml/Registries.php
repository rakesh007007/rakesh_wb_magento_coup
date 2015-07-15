<?php
class Webengage_Coup_Block_Adminhtml_Registries extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct(){
        $this->_controller = 'adminhtml_registries';
        $this->_blockGroup = 'webengage_coup';
        $this->_headerText = Mage::helper('webengage_coup')->__('Webengage coup');
        parent::__construct();
    }
}
