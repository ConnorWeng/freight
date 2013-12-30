<?php

class YSZKModel extends Model {

    protected $tableName = 'YSZK';

    protected $fields = array('ys_no', 'ys_type', 'supplier_id', 'buyer_name', 'kp_date', 'amount', 'currency', 'rmb_amount', 'sr_amount', 'hx_amount', 'buyer_rate', 'zx_end_date', 'xz_flag', 'xz_date', 'sr_op_date', 'xz_op_date', 'exp_flag');

    public function queryYSZK($supplier, $buyerName, $ysNo, $currency, $expFlag, $xzFlag, $srOpDate, $xzOpDate) {
        $sql  = "select u.enterprise_name supplier, y.* " .
                "  from freight_yszk y, freight_user u " .
                " where y.supplier_id = u.id ";

        if ($supplier != '') {
            $sql .= "  and u.enterprise_name = '$supplier' ";
        }
        if ($buyerName != '') {
            $sql .= "  and y.buyer_name = '$buyerName' ";
        }
        if ($ysNo != '') {
            $sql .= "  and y.ys_no = '$ysNo' ";
        }
        if ($currency != '') {
            $sql .= "  and y.currency = '$currency' ";
        }
        if ($expFlag != '') {
            $sql .= "  and y.exp_flag = '$expFlag' ";
        }
        if ($xzFlag != '') {
            $sql .= "  and y.xz_flag = '$xzFlag' ";
        }
        if ($srOpDate != '') {
            $sql .= "  and y.sr_op_date = '$srOpDate' ";
        }
        if ($xzOpDate != '') {
            $sql .= "  and y.xz_op_date = '$xzOpDate' ";
        }

        return $this->db->query($sql);
    }

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

    public function calBuyerRate($supplierId) {
        $sql  = "update freight_yszk y";
        $sql .= "   set buyer_rate =";
        $sql .= "       (select trunc((sum(s.sr_amount) - sum(s.hx_amount)) /";
        $sql .= "               (select sum(sr_amount) - sum(hx_amount)";
        $sql .= "                  from freight_yszk";
        $sql .= "                 where supplier_id = '$supplierId'";
        $sql .= "                   and xz_flag = 0), 4)";
        $sql .= "          from freight_yszk s";
        $sql .= "         group by s.supplier_id, s.buyer_name, s.xz_flag";
        $sql .= "        having s.supplier_id = '$supplierId' and s.xz_flag = 0 and s.buyer_name = y.buyer_name)";
        $sql .= " where supplier_id = '$supplierId'";

        $rs = $this->db->execute($sql);
        return $rs;
    }

    public function queryPool($supplier) {
        $sql =  "select t6.*, " .
                "       t6.zh_risk_amount - t6.used_risk_amount unuse_risk_amount, " .
                "       t6.used_risk_amount / t6.effect_amount current_diya_rate, " .
                "       t6.yq_amount / t6.whx_amount yq_rate, " .
                "       t6.above_3_month_whx_amount / t6.whx_amount above_3_month_whx_rate " .
                "  from (select t3.*, " .
                "               t4.zx_time, " .
                "               nvl(t4.diya_rate, 1) * t3.effect_amount zh_risk_amount, " .
                "               t5.used_risk_amount, " .
                "               t7.above_3_month_whx_amount " .
                "          from (select t1.*, " .
                "                       t2.yq_amount, " .
                "                       t1.whx_amount - t2.yq_amount effect_amount " .
                "                  from (select y.supplier_id, " .
                "                               sum(sr_amount) - sum(hx_amount) whx_amount, " .
                "                               max(u.enterprise_name) supplier " .
                "                          from freight_yszk y, freight_user u " .
                "                         where y.supplier_id = u.id " .
                "                           and y.xz_flag = '0' " .
                "                         group by y.supplier_id) t1, " .
                "                       (select y.supplier_id, " .
                "                               sum(sr_amount) - sum(hx_amount) yq_amount " .
                "                          from freight_yszk y " .
                "                         where y.xz_flag = '0' " .
                "                           and y.exp_flag = '1' " .
                "                         group by y.supplier_id) t2 " .
                "                 where t1.supplier_id = t2.supplier_id(+)) t3, " .
                "               (select l.supplier_id, l.diya_rate, l.zx_time " .
                "                  from freight_supplier_limit l) t4, " .
                "               (select r.supplier_id, " .
                "                       sum(loan_amount) - sum(init_security_amount) used_risk_amount " .
                "                  from freight_cz_record r " .
                "                 group by r.supplier_id) t5, " .
                "               (select y.supplier_id, " .
                "                       sum(y.sr_amount) - sum(y.hx_amount) above_3_month_whx_amount " .
                "                  from freight_yszk y, freight_supplier_limit l " .
                "                 where y.supplier_id = l.supplier_id " .
                "                   and floor(sysdate - to_date(y.kp_date, 'mm/dd/YYYY')) > " .
                "                       l.zx_time + 90 " .
                "                 group by y.supplier_id) t7 " .
                "         where t3.supplier_id = t4.supplier_id(+) " .
                "           and t3.supplier_id = t5.supplier_id(+) " .
                "           and t3.supplier_id = t7.supplier_id(+)) t6 " .
                " where t6.supplier like '%{$supplier}%' ";
        return $this->db->query($sql);
    }

    public function queryFuturePool($supplier) {
        $sql =  "select t1.*, " .
                "       t2.yq_amount / t1.whx_amount yq_rate, " .
                "       t3.after_5_days_yq_amount / t1.whx_amount after_5_days_yq_rate, " .
                "       t4.after_10_days_yq_amount / t1.whx_amount after_10_days_yq_rate, " .
                "       t5.above_3_month_yq_amount / t1.whx_amount above_3_month_yq_rate " .
                "  from (select y.supplier_id, " .
                "               max(u.enterprise_name) supplier, " .
                "               sum(y.sr_amount) - sum(y.hx_amount) whx_amount " .
                "          from freight_yszk y, freight_user u " .
                "         where y.supplier_id = u.id " .
                "           and xz_flag = '0' " .
                "         group by y.supplier_id) t1, " .
                "       (select y.supplier_id, sum(y.sr_amount) - sum(y.hx_amount) yq_amount " .
                "          from freight_yszk y " .
                "         where xz_flag = '0' " .
                "           and exp_flag = '1' " .
                "         group by y.supplier_id) t2, " .
                "       (select y.supplier_id, " .
                "               sum(y.sr_amount) - sum(y.hx_amount) after_5_days_yq_amount " .
                "          from freight_yszk y, freight_supplier_limit l " .
                "         where xz_flag = '0' " .
                "           and exp_flag = '0' " .
                "           and y.supplier_id = l.supplier_id(+) " .
                "           and floor(sysdate - to_date(y.kp_date, 'mm/dd/YYYY')) > " .
                "               l.zx_time - 5 " .
                "         group by y.supplier_id) t3, " .
                "         (select y.supplier_id, " .
                "               sum(y.sr_amount) - sum(y.hx_amount) after_10_days_yq_amount " .
                "          from freight_yszk y, freight_supplier_limit l " .
                "         where xz_flag = '0' " .
                "           and exp_flag = '0' " .
                "           and y.supplier_id = l.supplier_id(+) " .
                "           and floor(sysdate - to_date(y.kp_date, 'mm/dd/YYYY')) > " .
                "               l.zx_time - 10 " .
                "         group by y.supplier_id) t4, " .
                "         (select y.supplier_id, " .
                "               sum(y.sr_amount) - sum(y.hx_amount) above_3_month_yq_amount " .
                "          from freight_yszk y, freight_supplier_limit l " .
                "         where xz_flag = '0' " .
                "           and exp_flag = '1' " .
                "           and y.supplier_id = l.supplier_id(+) " .
                "           and floor(sysdate - to_date(y.kp_date, 'mm/dd/YYYY')) > " .
                "               l.zx_time + 90 " .
                "         group by y.supplier_id) t5 " .
                " where t1.supplier_id = t2.supplier_id(+) " .
                "   and t1.supplier_id = t3.supplier_id(+) " .
                "   and t1.supplier_id = t4.supplier_id(+) " .
                "   and t1.supplier_id = t5.supplier_id(+) " .
                "   and supplier like '%{$supplier}%'";

        return $this->db->query($sql);
    }

}

?>