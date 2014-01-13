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

    public function addIn() {
        $inDate = I('in_date');
        $inAmount = I('in_amount');
        $orderNo = I('order_no');
        $operUserId = session('user')['ID'];
        $rs = $this->inModel->addIn($inDate, $inAmount, $orderNo, $operUserId);
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

}

?>