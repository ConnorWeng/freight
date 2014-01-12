<?php

class KCXXAction extends CommonAction {

    protected $config = array(
        'need_login' => true
    );

    protected $inModel;

    function __construct() {
        parent::__construct();
        $this->inModel = D('In');
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

}

?>