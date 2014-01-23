<?php

class OutModel extends Model {

    protected $fields = array('serious_no', 'out_date', 'out_amount', 'receive_amount', 'receive_type', 'rate', 'order_no', 'build_date', 'oper_user_id');

    public function query($orderNo) {
        $sql = "select i.*, u.enterprise_name buyer from freight_out i, freight_order o, freight_user u where i.order_no like '%$orderNo%' and i.order_no = o.order_no and o.buyer_id = u.id";
        return fillKCArrayFields($this->db->query($sql));
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

    public function editOut($seriousNo, $outDate, $outAmount, $receiveAmount, $receiveType, $orderNo, $operUserId) {
        $where['serious_no'] = $seriousNo;
        $data['out_date'] = $outDate;
        $data['out_amount'] = $outAmount;
        $data['receive_amount'] = $receiveAmount;
        $data['receive_type'] = $receiveType;
        $data['order_no'] = $orderNo;
        $data['build_date'] = date('m/d/Y');
        $data['oper_user_id'] = $operUserId;
        return $this->where($where)->save($data);
    }

    public function delOut($seriousNo) {
        $where['serious_no'] = $seriousNo;
        return $this->where($where)->delete();
    }

}

?>