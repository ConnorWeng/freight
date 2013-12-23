<?php

class HxTempModel extends Model {

    protected $fields = array('batch_id', 'l_f_supplier', 'buyer_name', 'ys_no', 'amount', 'xz_amount', 'xz_date', 'excel');

    public function importData($batchId, $lfSupplier, $buyerName, $ysNo, $amount, $xzAmount, $xzDate, $excel) {
        $where['batch_id'] = $batchId;
        $where['l_f_supplier'] = $lfSupplier;
        $where['buyer_name'] = $buyerName;
        $where['ys_no'] = $ysNo;
        $where['amount'] = $amount;
        $where['xz_amount'] = $xzAmount;
        $where['xz_date'] = $xzDate;
        $where['excel'] = $excel;

        return $this->add($where);
    }

    public function deleteBatch($batchId) {
        $where['batch_id'] = $batchId;
        return $this->where($where)->delete();
    }

}

?>