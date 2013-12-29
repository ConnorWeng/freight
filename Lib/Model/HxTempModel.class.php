<?php

class HxTempModel extends Model {

    protected $fields = array('batch_id', 'supplier_id', 'buyer_name', 'ys_no', 'amount', 'xz_amount', 'xz_date', 'excel', 'currency');

    public function importData($batchId, $supplierId, $buyerName, $ysNo, $amount, $xzAmount, $xzDate, $excel, $currency) {
        $where['batch_id'] = $batchId;
        $where['supplier_id'] = $supplierId;
        $where['buyer_name'] = $buyerName;
        $where['ys_no'] = $ysNo;
        $where['amount'] = $amount;
        $where['xz_amount'] = $xzAmount;
        $where['xz_date'] = $xzDate;
        $where['excel'] = $excel;
        $where['currency'] = $currency;

        return $this->add($where);
    }

    public function queryByBatchId($batchId) {
        $sql = "select u.enterprise_name supplier, t.* " .
               "  from freight_hx_temp t, freight_user u " .
               " where t.supplier_id = u.id " .
               " and t.batch_id = '$batchId' ";
        return $this->db->query($sql);
    }

    public function deleteBatch($batchId) {
        $where['batch_id'] = $batchId;
        return $this->where($where)->delete();
    }

}

?>