<?php

class OrderAction extends CommonAction {

    protected $config = array(
        'need_login' => true,
    );

    protected $orderModel;

    function __construct() {
        parent::__construct();
        $this->orderModel = D('Order');
    }

    public function order() {
        $this->display();
    }

    public function searchOrder() {
        $orderNo = I('order_no');
        $buyerId = I('buyer_id');
        $supplierId = I('supplier_id');
        $loanFlag = I('loan_flag');
        $status = I('status');
        $rs = $this->orderModel->searchOrder($orderNo, $buyerId, $supplierId, $loanFlag, $status);
        $this->ajaxReturn($rs, 'JSON');
    }

    public function addOrder() {
        $orderNo = I('order_no');
        $buyerId = I('buyer_id');
        $supplierId = I('supplier_id');
        $sellerName = I('seller_name');
        $orderDate = I('order_date');
        $orderAmount = I('order_amount');
        $orderDesc = I('order_desc');
        $loanFlag = I('loan_flag');
        $loanNo = I('loan_no');
        $initSecurityAmount = I('init_security_amount');
        $status = I('status');
        $rs = $this->orderModel->addOrder($orderNo, $buyerId, $supplierId, $sellerName, $orderDate, $orderAmount, $orderDesc, $loanFlag,  $loanNo, $initSecurityAmount, $status);
        $this->ajaxReturn($rs, 'JSON');
    }

    public function editOrder() {
        $orderNo = I('order_no');
        $buyerId = I('buyer_id');
        $supplierId = I('supplier_id');
        $sellerName = I('seller_name');
        $orderDate = I('order_date');
        $orderAmount = I('order_amount');
        $orderDesc = I('order_desc');
        $loanFlag = I('loan_flag');
        $loanNo = I('loan_no');
        $initSecurityAmount = I('init_security_amount');
        $status = I('status');
        $rs = $this->orderModel->editOrder($orderNo, $buyerId, $supplierId, $sellerName, $orderDate, $orderAmount, $orderDesc, $loanFlag,  $loanNo, $initSecurityAmount, $status);
        $this->ajaxReturn($rs, 'JSON');
    }

}

?>