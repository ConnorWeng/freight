<?php

class YSZKModel extends Model {

    protected $tableName = 'YSZK';

    protected $fields = array('ys_no', 'ys_type', 'l_f_supplier', 'buyer_name', 'kp_date', 'amount', 'currency', 'rmb_amount', 'sr_amount', 'hx_amount', 'buyer_rate', 'zx_end_date', 'xz_flag', 'xz_date', 'sr_op_date', 'xz_op_date', 'exp_flag');

    public function checkDuplicate($ysNo) {
        $where['ys_no'] = $ysNo;
        $rs = $this->where($where)->select();
        if (count($rs) > 0) {
            return true;
        } else {
            return false;
        }
    }

}

?>