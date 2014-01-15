<?php

class OrderAction extends CommonAction {

    protected $config = array(
        'need_login' => true,
    );

    protected $orderModel;

    function __construct() {
        parent::__construct();
        $this->orderModel = D('Order');
    }

    public function order() {
        $this->display();
    }

}

?>