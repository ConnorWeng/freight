<?php

class CZXXAction extends CommonAction {

    protected $config = array(
        'need_login' => true,
    );

    protected $czxxModel;

    function __construct() {
        parent::__construct();
        $this->czxxModel = D('CzRecord');
    }

    public function czxx() {
        $this->display();
    }

    public function searchCZXX() {
        $supplier = I('supplier');
        $loanType = I('loan_type');
        $loanDate = I('loan_date');
        $loanEndDate = I('loan_end_date');
        $currency = I('currency');
        $endFlag = I('end_flag');

        $rs = $this->czxxModel->queryCZXX($supplier, $loanType, $loanDate, $loanEndDate, $currency, $endFlag);
        $this->ajaxReturn($rs, 'JSON');
    }

    public function addCZXX() {
        $loanNo = I('loan_no');
        $supplierId = supplierToId(I('supplier'));
        $loanAmount = I('loan_amount');
        $loanType = I('loan_type');
        $initSecurityAmount = I('init_security_amount');
        $loanDate = I('loan_date');
        $loanEndDate = I('loan_end_date');
        $currency = I('currency');
        $endFlag = I('end_flag');
        $operUserId = session('user')['ID'];
        $lastModifyDate = date('m/d/Y');

        $rs = $this->czxxModel->addCZXX($loanNo, $supplierId, $loanAmount, $loanType, $initSecurityAmount, $loanDate, $loanEndDate, $currency, $endFlag, $operUserId, $lastModifyDate);
        $this->ajaxReturn($rs, 'JSON');
    }

    public function editCZXX() {
        $loanNo = I('loan_no');
        $supplierId = supplierToId(I('supplier'));
        $loanAmount = I('loan_amount');
        $loanType = I('loan_type');
        $initSecurityAmount = I('init_security_amount');
        $loanDate = I('loan_date');
        $loanEndDate = I('loan_end_date');
        $currency = I('currency');
        $endFlag = I('end_flag');
        $operUserId = session('user')['ID'];
        $lastModifyDate = date('m/d/Y');

        $rs = $this->czxxModel->editCZXX($loanNo, $supplierId, $loanAmount, $loanType, $initSecurityAmount, $loanDate, $loanEndDate, $currency, $endFlag, $operUserId, $lastModifyDate);
        $this->ajaxReturn($rs, 'JSON');
    }

    public function delCZXX() {
        $loanNo = I('loan_no');
        $rs = $this->czxxModel->delCZXX($loanNo);
        $this->ajaxReturn($rs, 'JSON');
    }
}

?>