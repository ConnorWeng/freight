<?php

class CzRecordModel extends Model {

    protected $fields = array('loan_no', 'supplier_id', 'loan_amount', 'loan_type', 'init_security_amount', 'loan_date', 'loan_end_date', 'currency', 'end_flag', 'oper_user_id', 'last_modify_date');

    public function queryCZXX($supplier, $loanType, $loanDate, $loanEndDate, $currency, $endFlag) {
        $sql = "select r.*, u.enterprise_name supplier " .
               "  from freight_cz_record r, freight_user u " .
               " where r.supplier_id = u.id ";

        if ($supplier != '') {
            $sql .= "and u.enterprise_name = '{$supplier}' ";
        }
        if ($loanType != '') {
            $sql .= "and r.loan_type = '{$loanType}' ";
        }
        if ($loanDate != '') {
            $sql .= "and r.loan_date = '{$loanDate}' ";
        }
        if ($loanEndDate != '') {
            $sql .= "and r.loan_end_date = '{$loanEndDate}' ";
        }
        if ($currency != '') {
            $sql .= "and r.currency = '{$currency}' ";
        }
        if ($endFlag != '') {
            $sql .= "and r.end_flag = '{$endFlag}' ";
        }

        return $this->db->query($sql);
    }

    public function addCZXX($loanNo, $supplierId, $loanAmount, $loanType, $initSecurityAmount, $loanDate, $loanEndDate, $currency, $endFlag, $operUserId, $lastModifyDate) {
        $data['loan_no'] = $loanNo;
        $data['supplier_id'] = $supplierId;
        $data['loan_amount'] = $loanAmount;
        $data['loan_type'] = $loanType;
        $data['init_security_amount'] = $initSecurityAmount;
        $data['loan_date'] = $loanDate;
        $data['loan_end_date'] = $loanEndDate;
        $data['currency'] = $currency;
        $data['end_flag'] = $endFlag;
        $data['oper_user_id'] = $operUserId;
        $data['last_modify_date'] = $lastModifyDate;

        return $this->add($data);
    }

}

?>