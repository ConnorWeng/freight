<?php

class LimitAction extends CommonAction {

    protected $config = array(
        'need_login' => true,
    );

    protected $limitModel;

    function __construct() {
        parent::__construct();
        $this->limitModel = D('SupplierLimit');
    }

    public function index() {
        $this->display();
    }

    public function searchLimit() {
        $supplierName = I('supplier_name');
        $supplierId = enterpriseNameToId($supplierName);
        $rs = $this->limitModel->searchLimit($supplierId);
        $this->ajaxReturn($rs, 'JSON');
    }

    public function addLimit() {
        $supplierName = I('supplier_name');
        $supplierId = enterpriseNameToId($supplierName);
        $amount = I('amount');
        $riskAmount = I('risk_amount');
        $zxTime = I('zx_time');
        $diyaRate = I('diya_rate');
        $startDate = I('start_date');
        $endDate = I('end_date');
        $rs = $this->limitModel->addLimit($supplierId, $amount, $riskAmount, $zxTime, $diyaRate, $startDate, $endDate);
        $this->ajaxReturn($rs, 'JSON');
    }

    public function editLimit() {
        $supplierId = I('supplier_id');
        $amount = I('amount');
        $riskAmount = I('risk_amount');
        $zxTime = I('zx_time');
        $diyaRate = I('diya_rate');
        $startDate = I('start_date');
        $endDate = I('end_date');
        $rs = $this->limitModel->editLimit($supplierId, $amount, $riskAmount, $zxTime, $diyaRate, $startDate, $endDate);
        $this->ajaxReturn($rs, 'JSON');
    }

    public function delLimit() {
        $supplierId = I('supplier_id');
        $rs = $this->limitModel->delLimit($supplierId);
        $this->ajaxReturn($rs, 'JSON');
    }

}

?>