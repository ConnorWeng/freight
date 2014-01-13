<?php

class OutModel extends Model {

    protected $fields = array('serious_no', 'out_date', 'out_amount', 'receive_amount', 'receive_type', 'rate', 'order_no', 'build_date', 'oper_user_id');

    public function query($orderNo) {
        $where = array();
        if ($orderNo != '') {
            $where['order_no'] = $orderNo;
        }
        return fillKCArrayFields($this->where($where)->select());
    }

    public function addOut($outDate, $outAmount, $receiveAmount, $receiveType, $orderNo, $operUserId) {
        $data['serious_no'] = uniqid();
        $data['out_date'] = $outDate;
        $data['out_amount'] = $outAmount;
        $data['receive_amount'] = $receiveAmount;
        $data['receive_type'] = $receiveType;
        $data['order_no'] = $orderNo;
        $data['build_date'] = date('m/d/Y');
        $data['oper_user_id'] = $operUserId;
        return $this->add($data);
    }

}

?>