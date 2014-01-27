<?php

class KCXXAction extends CommonAction {

    protected $config = array(
        'need_login' => true
    );

    protected $inModel;
    protected $outModel;

    function __construct() {
        parent::__construct();
        $this->inModel = D('In');
        $this->outModel = D('Out');
    }

    public function kcxx() {
        $this->display();
    }

    public function query() {
        $inOutType = I('in_out_type');
        $orderNo = I('order_no');
        $inList = array();
        $outList = array();
        if ($inOutType == 'in') {
            $inList = $this->inModel->query($orderNo);
        } else if ($inOutType == 'out') {
            $outList = $this->outModel->query($orderNo);
        } else if ($inOutType == 'all') {
            $inList = $this->inModel->query($orderNo);
            $outList = $this->outModel->query($orderNo);
        }
        $rs = array_merge($inList, $outList);
        $this->ajaxReturn($rs, 'JSON');
    }

    public function addIn() {
        $inDate = I('in_date');
        $inAmount = I('in_amount');
        $orderNo = I('order_no');
        $operUserId = session('user')['ID'];
        $rs = $this->inModel->addIn($inDate, $inAmount, $orderNo, $operUserId);
        $this->ajaxReturn($rs, 'JSON');
    }

    public function editIn() {
        $seriousNo = I('serious_no');
        $inDate = I('in_date');
        $inAmount = I('in_amount');
        $orderNo = I('order_no');
        $rs = $this->inModel->editIn($seriousNo, $inDate, $inAmount, $orderNo, $operUserId);
        $this->ajaxReturn($rs, 'JSON');
    }

    public function delIn() {
        $seriousNo = I('serious_no');
        $rs = $this->inModel->delIn($seriousNo);
        $this->ajaxReturn($rs, 'JSON');
    }

    public function addOut() {
        $orderNo = I('order_no');
        $outDate = I('out_date');
        $outAmount = I('out_amount');
        $receiveAmount = I('receive_amount');
        $receiveType = I('receive_type');
        $operUserId = session('user')['ID'];

        $orderModel = D('Order');
        $order = $orderModel->queryOrderByNo($orderNo);
        $this->validateOut($order, $outDate, $outAmount, $receiveAmount, $receiveType);

        $rs = $this->outModel->addOut($outDate, $outAmount, $receiveAmount, $receiveType, $orderNo, $operUserId);
        $this->ajaxReturn($rs, 'JSON');
    }

    public function validateOut($order, $outDate, $outAmount, $receiveAmount, $receiveType) {
        if ($order == null) {
            $this->ajaxReturn('订单不存在', 'JSON');
        }

        $orderDateTime = new DateTime($order['ORDER_DATE']);
        $outDateTime = new DateTime($outDate);
        if ($outDateTime < $orderDateTime) {
            $this->ajaxReturn('出库日期不能早于订单日期', 'JSON');
        }
    }

    public function editOut() {
        $seriousNo = I('serious_no');
        $outDate = I('out_date');
        $outAmount = I('out_amount');
        $receiveAmount = I('receive_amount');
        $receiveType = I('receive_type');
        $orderNo = I('order_no');
        $operUserId = session('user')['ID'];
        $rs = $this->outModel->editOut($seriousNo, $outDate, $outAmount, $receiveAmount, $receiveType, $orderNo, $operUserId);
        $this->ajaxReturn($rs, 'JSON');
    }

    public function export() {
        $inOutType = I('in_out_type');
        $orderNo = I('order_no');
        $inList = array();
        $outList = array();
        if ($inOutType == 'in') {
            $inList = $this->inModel->query($orderNo);
        } else if ($inOutType == 'out') {
            $outList = $this->outModel->query($orderNo);
        } else if ($inOutType == 'all') {
            $inList = $this->inModel->query($orderNo);
            $outList = $this->outModel->query($orderNo);
        }
        $rs = array_merge($inList, $outList);
        exportExcel('库存信息', array(
            array('BUILD_DATE','操作日期'),
            array('','操作类型'),
            array('IN_AMOUNT','入库货值'),
            array('OUT_AMOUNT', '出货货值'),
            array('RECEIVE_AMOUNT', '订单金额'),
            array('', '应收款金额'),
            array('', '收款／放贷比'),
            array('ORDER_NO', '所属订单编号'),
            array('BUYER', '经销商')), $rs);
    }

    public function delOut() {
        $seriousNo = I('serious_no');
        $rs = $this->outModel->delOut($seriousNo);
        $this->ajaxReturn($rs, 'JSON');
    }

}

?>