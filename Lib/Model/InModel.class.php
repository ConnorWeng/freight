<?php

class InModel extends Model {

    protected $fields = array('serious_no', 'in_date', 'in_amount', 'order_no', 'build_date', 'oper_user_id');

    public function query($orderNo) {
        $sql = "select i.*, u.enterprise_name buyer from freight_in i, freight_order o, freight_user u where i.order_no like '%$orderNo%' and i.order_no = o.order_no and o.buyer_id = u.id";
        return fillKCArrayFields($this->db->query($sql));
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

    public function editIn($seriousNo, $inDate, $inAmount, $orderNo, $operUserId) {
        $where['serious_no'] = $seriousNo;
        $data['in_date'] = $inDate;
        $data['in_amount'] = $inAmount;
        $data['order_no'] = $orderNo;
        $data['build_date'] = date('m/d/Y');
        $data['oper_user_id'] = $operUserId;
        return $this->where($where)->save($data);
    }

    public function delIn($seriousNo) {
        $where['serious_no'] = $seriousNo;
        return $this->where($where)->delete();
    }

}

?>