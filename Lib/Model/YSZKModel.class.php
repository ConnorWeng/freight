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

    public function queryPool($supplier) {
        $sql =  "select tmp3.*, " .
                "       tmp3.zhkdck - used_ck unused_ck, " .
                "       tmp3.used_ck / tmp3.effect_amount current_dy_rate, " .
                "       tmp3.exp_amount / tmp3.whx_sr_amount exp_rate, " .
                "       (select sum(sr_amount) - sum(hx_amount) " .
                "                                  from (select * from freight_yszk where (sysdate - to_date(kp_date, 'mm/dd/yyyy')) < 90) " .
                "                                 group by l_f_Supplier, xz_flag, exp_flag " .
                "                                having xz_flag = 0 and exp_flag = 1) / tmp3.whx_sr_amount month_exp_whx_rate " .
                "  from (select tmp2.*, " .
                "               effect_amount * " .
                "               (select hdrz_rate " .
                "                  from freight_supplier_limit " .
                "                 where supplier_id = tmp2.supplier) zhkdck, " .
                "               (select sum(cz_amount) - sum(init_secure_amount) " .
                "                  from freight_cz_record " .
                "                 group by supplier_id) used_ck " .
                "          from (select tmp1.*, whx_sr_amount - exp_amount effect_amount " .
                "                  from (select l_f_supplier supplier, " .
                "                               sum(sr_amount) - sum(hx_amount) whx_sr_amount, " .
                "                               (select sum(sr_amount) - sum(hx_amount) " .
                "                                  from freight_yszk s " .
                "                                 group by s.l_f_Supplier, s.xz_flag, s.exp_flag " .
                "                                having xz_flag = 0 and s.exp_flag = 1) exp_amount " .
                "                          from freight_yszk y " .
                "                         group by l_f_supplier, xz_flag, exp_flag " .
                "                        having xz_flag = 0) tmp1) tmp2) tmp3 where supplier like '%{$supplier}%'";

        return $this->db->query($sql);
    }

    public function queryFuturePool() {
        $sql = '';
        return $this->db->query($sql);
    }
}

?>