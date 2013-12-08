
<?php
class YSZKAction extends CommonAction {

    public function queryPool() {
        $this->defaultDisplay();
    }

    public function yszk() {
        $this->defaultDisplay();
    }

    public function searchYSZK() {
        $lfSupplier = I('l_f_supplier');
        $buyerName = I('buyer_name');
        $ysNo = I('ys_no');
        $currency = I('currency');
        $expFlag = I('exp_flag');
        $xzFlag = I('xz_flag');
        $srDate = I('sr_date');
        $xzDate = I('xz_date');

        if ($lfSupplier != '') {
            $where['l_f_supplier'] = $lfSupplier;
        }
        if ($buyerName != '') {
            $where['buyer_name'] = $buyerName;
        }
        if ($ysNo != '') {
            $where['ys_no'] = $ysNo;
        }
        if ($currency != '') {
            $where['currency'] = $currency;
        }
        if ($expFlag != '') {
            //$where['exp_flag'] = $expFlag;
        }
        if ($xzFlag != '') {
            $where['xz_flag'] = $xzFlag;
        }
        if ($srDate != '') {
            $where['sr_date'] = $srDate;
        }
        if ($xzDate != '') {
            $where['xz_date'] = $xzDate;
        }
        $yszx = D('YSZK');
        $rs = $yszx->where($where)->select();
        $this->ajaxReturn($rs, 'JSON');
    }

}

?>