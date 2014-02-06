<?php

class OrderModel extends Model {

    protected $fields = array('order_no', 'buyer_id', 'supplier_id', 'seller_name', 'order_date', 'order_amount', 'order_desc', 'loan_flag', 'loan_no', 'init_security_amount', 'status', 'build_date', 'modify_date', 'oper_user_id');

    public function searchOrder($orderNo, $buyerId, $supplierId, $loanFlag, $status) {
        $sql = "select o.*, u1.enterprise_name buyer, u2.enterprise_name supplier from freight_order o, freight_user u1, freight_user u2 where o.buyer_id = u1.id(+) and o.supplier_id = u2.id(+) and o.order_no like '%$orderNo%' and o.buyer_id like '%$buyerId%' and o.supplier_id like '%$supplierId%' and o.loan_flag like '%$loanFlag%' and o.status like '%$status%'";
        return $this->db->query($sql);
    }

    public function queryOrderByNo($orderNo) {
        $sql = "select o.*, u1.enterprise_name buyer, u2.enterprise_name supplier from freight_order o, freight_user u1, freight_user u2 where o.buyer_id = u1.id(+) and o.supplier_id = u2.id(+) and o.order_no = '$orderNo'";
        $rs = $this->db->query($sql);
        if (count($rs) > 0) {
            return $rs[0];
        } else {
            return null;
        }
    }

    public function addOrder($orderNo, $buyerId, $supplierId, $sellerName, $orderDate, $orderAmount, $orderDesc, $loanFlag,  $loanNo, $initSecurityAmount, $status, $operUserId) {
       $data['order_no'] = $orderNo;
       $data['buyer_id'] = $buyerId;
       $data['supplier_id'] = $supplierId;
       $data['seller_name'] = $sellerName;
       $data['order_date'] = $orderDate;
       $data['order_amount'] = $orderAmount;
       $data['order_desc'] = $orderDesc;
       $data['loan_flag'] = $loanFlag;
       $data['loan_no'] = $loanNo;
       $data['init_security_amount'] = $initSecurityAmount;
       $data['status'] = $status;
       $data['build_date'] = date('m/d/Y');
       $data['oper_user_id'] = $operUserId;
       return $this->add($data);
    }

    public function editOrder($orderNo, $buyerId, $supplierId, $sellerName, $orderDate, $orderAmount, $orderDesc, $loanFlag, $loanNo, $initSecurityAmount, $status, $operUserId) {
       $where['order_no'] = $orderNo;
       $data['buyer_id'] = $buyerId;
       $data['supplier_id'] = $supplierId;
       $data['seller_name'] = $sellerName;
       $data['order_date'] = $orderDate;
       $data['order_amount'] = $orderAmount;
       $data['order_desc'] = $orderDesc;
       $data['loan_flag'] = $loanFlag;
       $data['loan_no'] = $loanNo;
       $data['init_security_amount'] = $initSecurityAmount;
       $data['status'] = $status;
       $data['modify_date'] = date('m/d/Y');
       $data['oper_user_id'] = $operUserId;
       return $this->where($where)->save($data);
    }

    public function delOrder($orderNo) {
        $where['order_no'] = $orderNo;
        return $this->where($where)->delete();
    }

    public function getOrderNos($term) {
        $sql = "select order_no from freight_order where order_no like '%$term%'";
        return $this->db->query($sql);
    }

}

?>