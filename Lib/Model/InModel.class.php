<?php

class InModel extends Model {

    protected $fields = array('serious_no', 'in_date', 'in_amount', 'order_no', 'build_date', 'oper_user_id');

    public function query($orderNo) {
        $where = array();
        if ($orderNo != '') {
            $where['order_no'] = $orderNo;
        }
        return fillKCArrayFields($this->where($where)->select());
    }

    public function addIn($inDate, $inAmount, $orderNo, $operUserId) {
        $data['serious_no'] = uniqid();
        $data['in_date'] = $inDate;
        $data['in_amount'] = $inAmount;
        $data['order_no'] = $orderNo;
        $data['build_date'] = date('m/d/Y');
        $data['oper_user_id'] = $operUserId;
        return $this->add($data);
    }

}

?>