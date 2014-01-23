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
        $supplierId = enterpriseNameToId(I('supplier'));
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
        $supplierId = enterpriseNameToId(I('supplier'));
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

    public function export() {
        $supplier = I('supplier');
        $loanType = I('loan_type');
        $loanDate = I('loan_date');
        $loanEndDate = I('loan_end_date');
        $currency = I('currency');
        $endFlag = I('end_flag');
        $rs = $this->czxxModel->queryCZXX($supplier, $loanType, $loanDate, $loanEndDate, $currency, $endFlag);
        exportExcel('出账信息', array(
            array('LOAN_NO','业务编号'),
            array('SUPPLIER','物流金融服务商'),
            array('LOAN_AMOUNT','出账金额'),
            array('LOAN_TYPE', '出账方式'),
            array('INIT_SECURITY_AMOUNT', '初始保证金'),
            array('LOAN_DATE', '出账日期'),
            array('LOAN_END_DATE', '到期日'),
            array('CURRENCY', '币种'),
            array('END_FLAG', '结清标识')), $rs);
    }
}

?>