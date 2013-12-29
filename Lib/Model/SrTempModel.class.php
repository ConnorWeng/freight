<?php

class SrTempModel extends Model {

    protected $fields = array('batch_id', 'supplier_id', 'buyer_name', 'ys_no', 'kp_date', 'ys_end_date', 'amount', 'currency', 'excel');

    public function importData($batchId, $supplierId, $buyerName, $ysNo, $kpDate, $ysEndDate, $amount, $currency, $excel) {
        $where['batch_id'] = $batchId;
        $where['supplier_id'] = $supplierId;
        $where['buyer_name'] = $buyerName;
        $where['ys_no'] = $ysNo;
        $where['kp_date'] = $kpDate;
        $where['ys_end_date'] = $ysEndDate;
        $where['amount'] = $amount;
        $where['currency'] = $currency;
        $where['excel'] = $excel;

        return $this->add($where);
    }

    public function queryByBatchId($batchId) {
        $sql = "select u.enterprise_name supplier, t.* " .
               "  from freight_sr_temp t, freight_user u " .
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