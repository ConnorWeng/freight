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
        exportExcel('������Ϣ', array(
            array('BUYER','������'),
            array('SELLER_NAME','���ĳ���'),
            array('ORDER_NO','�������'),
            array('ORDER_DATE', '�µ�����'),
            array('ORDER_AMOUNT', '�������'),
            array('ORDER_DESC', '��������'),
            array('INIT_SECURITY_AMOUNT', '��ʼ��֤��'),
            array('LOAN_FLAG', '���ʱ�ʶ'),
            array('', '��������'),
            array('', '���ʽ��'),
            array('', '���ʵ�����'),
            array('', '���������'),
            array('STATUS', '����״̬')), $rs);
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