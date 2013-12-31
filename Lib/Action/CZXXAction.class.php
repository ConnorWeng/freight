<?php

class CZXXAction extends CommonAction {

    protected $config = array(
        'need_login' => true,
    );

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

        $czxxModel = D('CzRecord');
        $rs = $czxxModel->queryCZXX($supplier, $loanType, $loanDate, $loanEndDate, $currency, $endFlag);
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

        $czxxModel = D('CzRecord');
        $rs = $czxxModel->addCZXX($loanNo, $supplierId, $loanAmount, $loanType, $initSecurityAmount, $loanDate, $loanEndDate, $currency, $endFlag, $operUserId, $lastModifyDate);
        $this->ajaxReturn($rs, 'JSON');
    }

}

?>