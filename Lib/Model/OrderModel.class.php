<?php

class OrderModel extends Model {

    protected $fields = array('order_no', 'buyer_id', 'supplier_id', 'seller_name', 'order_date', 'order_amount', 'order_desc', 'loan_flag', 'loan_no', 'init_security_amount', 'status', 'build_date', 'modify_date', 'oper_user_id');

    public function searchOrder($orderNo, $buyerId, $supplierId, $loanFlag, $status) {
        if ($orderNo != '') {
            $where['order_no'] = $orderNo;
        }
        if ($buyerId != '') {
            $where['buyer_id'] = $buyerId;
        }
        if ($supplierId != '') {
            $where['supplier_id'] = $supplierId;
        }
        if ($loanFlag != '') {
            $where['loan_flag'] = $loanFlag;
        }
        if ($status != '') {
            $where['status'] = $status;
        }
        return $this->where($where)->select();
    }

    public function addOrder($orderNo, $buyerId, $supplierId, $sellerName, $orderDate, $orderAmount, $orderDesc, $loanFlag,  $loanNo, $initSecurityAmount, $status) {
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
       return $this->add($data);
    }

    public function editOrder($orderNo, $buyerId, $supplierId, $sellerName, $orderDate, $orderAmount, $orderDesc, $loanFlag,  $loanNo, $initSecurityAmount, $status) {
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
       return $this->where($where)->save($data);
    }

}

?>