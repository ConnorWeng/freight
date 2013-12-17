<?php

class SrTempModel extends Model {

    protected $fields = array('batch_id', 'l_f_supplier', 'buyer_name', 'ys_no', 'kp_date', 'ys_end_date', 'ori_amount', 'currency');

    public function importData($batchId, $lfSupplier, $buyerName, $ysNo, $kpDate, $ysEndDate, $oriAmount, $currency) {
        $where['batch_id'] = $batchId;
        $where['l_f_supplier'] = $lfSupplier;
        $where['buyer_name'] = $buyerName;
        $where['ys_no'] = $ysNo;
        $where['kp_date'] = $kpDate;
        $where['ys_end_date'] = $ysEndDate;
        $where['ori_amount'] = $oriAmount;
        $where['currency'] = $currency;

        return $this->add($where);
    }

    public function deleteBatch($batchId) {
        $where['batch_id'] = $batchId;
        return $this->where($where)->delete();
    }

}

?>