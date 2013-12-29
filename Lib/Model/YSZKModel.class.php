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
        $sql = '';
        return $this->db->query($sql);
    }

    public function queryFuturePool() {
        $sql = '';
        return $this->db->query($sql);
    }

}

?>