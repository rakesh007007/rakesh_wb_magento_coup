<?php
class Webengage_Coup_Adminhtml_CoupController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
        return $this;
    }
	public function editAction()
    {
        $typeId = $this->getRequest()->getParam('entity_id');
		$this->loadLayout();
        $this->renderLayout();
        return $this;
    }
	
    public function newAction()
    {
        $this->loadLayout();
        $this->renderLayout();
        return $this;
    }
    public function deactivateAction(){
    $all = $this->getRequest()->getParams();
    var_dump($all);
    $IdLeaveIntent = $this->getRequest()->getParam('IdLeaveIntent');
    $IdItemDrop = $this->getRequest()->getParam('IdItemDrop');
    $helper = Mage::helper('webengage_coup');
    $helper->NotificationDeActivate($IdLeaveIntent);
    $helper->NotificationDeActivate($IdItemDrop);
    $this->_redirect('*/*/');
    //$redirect_url=$this->url->link('module/rakeshweb/index', 'token=' . $this->session->data['token'], 'SSL');
    //$this->response->redirect($redirect_url);


}
public function activateAction(){
    $IdLeaveIntent = $this->getRequest()->getParam('IdLeaveIntent');
    $IdItemDrop = $this->getRequest()->getParam('IdItemDrop');
    $helper = Mage::helper('webengage_coup');
    $helper->NotificationActivate($IdLeaveIntent);
    $helper->NotificationActivate($IdItemDrop);
    $this->_redirect('*/*/');
    //$redirect_url=$this->url->link('module/rakeshweb/index', 'token=' . $this->session->data['token'], 'SSL');
    //$this->response->redirect($redirect_url);


}
public function resetAction(){
    $IdLeaveIntent = $this->getRequest()->getParam('IdLeaveIntent');
    $IdItemDrop = $this->getRequest()->getParam('IdItemDrop');
    $helper = Mage::helper('webengage_coup');
    $helper->NotificationDelete($IdLeaveIntent);
    $helper->NotificationDelete($IdItemDrop);
    $this->_redirect('*/*/');
    //$redirect_url=$this->url->link('module/rakeshweb/index', 'token=' . $this->session->data['token'], 'SSL');
    //$this->response->redirect($redirect_url);


}
    public function mainFormSubmitAction()
        {
        $helper = Mage::helper('webengage_coup');
        //$redirect_url=$this->url->link('module/rakeshweb', 'token=' . $this->session->data['token'], 'SSL'); 
        //$data['urlnew'] = $redirect_url;
        $ting = $this->getRequest()->getParams();
        var_dump($ting);
        $coupons = $this->getRequest()->getParam('coupons');
        $theme = $this->getRequest()->getParam('theme');
        $msgLeaveIntent = $this->getRequest()->getParam('msgLeaveIntent');
        $msgCartDrop =  $this->getRequest()->getParam('msgCartDrop');
        //echo 'yo';
        //echo $theme.'`'.$msgLeaveIntent.'`'.$msgCartDrop;
        $implodedCoupons = (implode('|',$coupons));
        //$this->load->model('module/rakeshweb');
        $leaveId = $helper->NotificationCreate($implodedCoupons,$msgLeaveIntent,'Apply','[[pdurl]]',$theme);
        $dropId = $helper->NotificationCreate($implodedCoupons,$msgCartDrop,'Apply','[[pdurl]]',$theme);
        $helper->NotificationSetDropTag($dropId);
        $helper->NotificationSetLeaveTag($leaveId);
        $this->_redirect('*/*/');
         //$redirect_url=$this->url->link('module/rakeshweb/index','token=' . $this->session->data['token'], 'SSL');
         //$this->response->redirect($redirect_url);


        //$this->response->redirect($redirect_url);


}
public function editFormSubmitAction()
        {
        $helper = Mage::helper('webengage_coup');
        //$redirect_url=$this->url->link('module/rakeshweb', 'token=' . $this->session->data['token'], 'SSL'); 
        //$data['urlnew'] = $redirect_url;
        $ting = $this->getRequest()->getParams();
        var_dump($ting);
        $coupons = $this->getRequest()->getParam('coupons');
        $theme = $this->getRequest()->getParam('theme');
        $msgLeaveIntent = $this->getRequest()->getParam('msgLeaveIntent');
        $msgCartDrop =  $this->getRequest()->getParam('msgCartDrop');
        $IdLeaveIntent = $this->getRequest()->getParam('IdLeaveIntent');
        $IdItemDrop = $this->getRequest()->getParam('IdItemDrop');
        //echo 'yo';
        //echo $theme.'`'.$msgLeaveIntent.'`'.$msgCartDrop;
        $implodedCoupons = (implode('|',$coupons));
        //$this->load->model('module/rakeshweb');
        $helper->NotificationEdit($implodedCoupons,$msgLeaveIntent,'Apply','[[pdurl]]',$theme,$IdLeaveIntent);
        $helper->NotificationEdit($implodedCoupons,$msgCartDrop,'Apply','[[pdurl]]',$theme,$IdItemDrop);
        $this->_redirect('*/*/');
         //$redirect_url=$this->url->link('module/rakeshweb/index','token=' . $this->session->data['token'], 'SSL');
         //$this->response->redirect($redirect_url);


        //$this->response->redirect($redirect_url);


}



}
