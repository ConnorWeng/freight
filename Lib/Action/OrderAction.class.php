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
        $this->assign(array(
            'supplier' => session('user')['ENTERPRISE_NAME']
        ));
        $this->display();
    }

    public function searchOrder() {
        $orderNo = I('order_no');
        $buyerId = enterpriseNameToId(I('buyer'));
        $supplierId = enterpriseNameToId(I('supplier'));
        $loanFlag = I('loan_flag');
        $status = I('status');
        $rs = $this->orderModel->searchOrder($orderNo, $buyerId, $supplierId, $loanFlag, $status);
        $this->ajaxReturn($rs, 'JSON');
    }

    public function addOrder() {
        $orderNo = I('order_no');
        $buyerId = enterpriseNameToId(I('buyer'));
        $supplierId = enterpriseNameToId(I('supplier'));
        $sellerName = I('seller_name');
        $orderDate = I('order_date');
        $orderAmount = I('order_amount');
        $orderDesc = I('order_desc');
        $loanFlag = I('loan_flag');
        $loanNo = I('loan_no');
        $initSecurityAmount = I('init_security_amount');
        $status = I('status');
        $operUserId = session('user')['ID'];
        $rs = $this->orderModel->addOrder($orderNo, $buyerId, $supplierId, $sellerName, $orderDate, $orderAmount, $orderDesc, $loanFlag,  $loanNo, $initSecurityAmount, $status, $operUserId);
        $this->ajaxReturn($rs, 'JSON');
    }

    public function editOrder() {
        $orderNo = I('order_no');
        $buyerId = enterpriseNameToId(I('buyer'));
        $supplierId = enterpriseNameToId(I('supplier'));
        $sellerName = I('seller_name');
        $orderDate = I('order_date');
        $orderAmount = I('order_amount');
        $orderDesc = I('order_desc');
        $loanFlag = I('loan_flag');
        $loanNo = I('loan_no');
        $initSecurityAmount = I('init_security_amount');
        $status = I('status');
        $operUserId = session('user')['ID'];
        $rs = $this->orderModel->editOrder($orderNo, $buyerId, $supplierId, $sellerName, $orderDate, $orderAmount, $orderDesc, $loanFlag,  $loanNo, $initSecurityAmount, $status, $operUserId);
        $this->ajaxReturn($rs, 'JSON');
    }

    public function delOrder() {
        $orderNo = I('order_no');
        $rs = $this->orderModel->delOrder($orderNo);
        $this->ajaxReturn($rs, 'JSON');
    }

    public function export() {
        $orderNo = I('order_no');
        $buyerId = enterpriseNameToId(I('buyer'));
        $supplierId = enterpriseNameToId(I('supplier'));
        $loanFlag = I('loan_flag');
        $status = I('status');
        $rs = $this->orderModel->searchOrder($orderNo, $buyerId, $supplierId, $loanFlag, $status);
        exportExcel('订单信息', array(
            array('BUYER','经销商'),
            array('SELLER_NAME','核心厂商'),
            array('ORDER_NO','订单编号'),
            array('ORDER_DATE', '下单日期'),
            array('ORDER_AMOUNT', '订单金额'),
            array('ORDER_DESC', '订单描述'),
            array('INIT_SECURITY_AMOUNT', '初始保证金'),
            array('LOAN_FLAG', '融资标识'),
            array('', '融资日期'),
            array('', '融资金额'),
            array('', '融资到期日'),
            array('', '赎货到期日'),
            array('STATUS', '订单状态')), $rs);
    }

    public function getOrderNos() {
        $term = I('term');
        $rs = $this->orderModel->getOrderNos($term);
        $result = array();
        foreach ($rs as $row) {
            array_push($result, $row['ORDER_NO']);
        }
        $this->ajaxReturn($result, 'JSON');
    }

}

?>