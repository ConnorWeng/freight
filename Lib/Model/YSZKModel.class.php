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

    public function import($data) {
        return $this->add($data);
    }

    public function hx($ysNo, $xzAmount, $xzDate, $xzOpDate) {
        $where['ys_no'] = $ysNo;
        $this->where($where)->setField('xz_date', $xzDate);
        $this->where($where)->setField('xz_op_date', $xzOpDate);
        $this->where($where)->setInc('hx_amount', floatval($xzAmount));
    }

    public function updateXzFlag() {
        $sql = 'update freight_yszk set xz_flag = 1 where amount - hx_amount <= 0';
        $rs = $this->db->execute($sql);
        return $rs;
    }

    public function calBuyerRate($supplier) {
        $sql  = "update freight_yszk y";
        $sql .= "   set buyer_rate =";
        $sql .= "       (select trunc((sum(s.sr_amount) - sum(s.hx_amount)) /";
        $sql .= "               (select sum(sr_amount) - sum(hx_amount)";
        $sql .= "                  from freight_yszk";
        $sql .= "                 where l_f_supplier = '$supplier'";
        $sql .= "                   and xz_flag = 0), 4)";
        $sql .= "          from freight_yszk s";
        $sql .= "         group by s.l_f_supplier, s.buyer_name, s.xz_flag";
        $sql .= "        having s.l_f_supplier = '$supplier' and s.xz_flag = 0 and s.buyer_name = y.buyer_name)";
        $sql .= " where l_f_supplier = '$supplier'";

        $rs = $this->db->execute($sql);
        return $rs;
    }
}

?>