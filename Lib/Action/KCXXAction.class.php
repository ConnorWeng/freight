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
        $outDate = I('out_date');
        $outAmount = I('out_amount');
        $receiveAmount = I('receive_amount');
        $receiveType = I('receive_type');
        $orderNo = I('order_no');
        $operUserId = session('user')['ID'];
        $rs = $this->outModel->addOut($outDate, $outAmount, $receiveAmount, $receiveType, $orderNo, $operUserId);
        $this->ajaxReturn($rs, 'JSON');
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

    public function delOut() {
        $seriousNo = I('serious_no');
        $rs = $this->outModel->delOut($seriousNo);
        $this->ajaxReturn($rs, 'JSON');
    }

}

?>