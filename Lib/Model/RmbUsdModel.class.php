<?php

class RmbUsdModel extends Model {

    protected $fields = array('id', 'start_date', 'end_date', 'rate');

    private static $RATES = array();

    public function getRate($currency) {
        if ($currency == 'USD' && isset(self::$RATES[$currency])) {
            return self::$RATES[$currency];
        }

        $where['id'] = $currency;
        $rs = $this->where($where)->find();
        $RATES[$currency] = $rs['RATE'];
        return $rs['RATE'];
    }

}

?>