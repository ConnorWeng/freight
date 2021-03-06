<?php

class SupplierLimitModel extends Model {

    protected $fields = array('supplier_id', 'amount', 'risk_amount', 'zx_time', 'one_buyer_rate', 'used_amount', 'used_risk_amount', 'security_balance', 'diya_rate', 'buyer_list', 'start_date', 'end_date');

    public function isExpire($supplierId, $kpDate) {
        $sql  = " select floor(sysdate - to_date('".$kpDate."', 'mm/dd/yyyy')) -";
        $sql .= "   nvl((select l.zx_time";
        $sql .= "     from ".C('DB_PREFIX')."supplier_limit l, ".C('DB_PREFIX')."user u";
        $sql .= "   where l.supplier_id = u.id";
        $sql .= "     and u.id = '".$supplierId."'),0) delta from dual";

        $rs = $this->db->query($sql);
        if (count($rs) > 0) {
            if (intval($rs[0]['DELTA']) > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            throw new Exception('未查询到该物流金融服务商的债项管理期信息');
        }
    }

    public function getZxTime($supplier) {
        $sql = "select * from freight_supplier_limit l, freight_user u where l.supplier_id = u.id and u.enterprise_name = '$supplier'";
        $rs = $this->db->query($sql);
        if (count($rs) > 0) {
            return $rs[0]['ZX_TIME'];
        } else {
            return $rs;
        }
    }

    public function searchLimit($supplierId) {
        $sql = "select l.*, u.enterprise_name supplier_name from freight_supplier_limit l, freight_user u where l.supplier_id = u.id and l.supplier_id like '%$supplierId%'";
        return $this->db->query($sql);
    }

    public function addLimit($supplierId, $amount, $riskAmount, $zxTime, $diyaRate, $startDate, $endDate) {
        $data['supplier_id'] = $supplierId;
        $data['amount'] = $amount;
        $data['risk_amount'] = $riskAmount;
        $data['zx_time'] = $zxTime;
        $data['diya_rate'] = $diyaRate;
        $data['start_date'] = $startDate;
        $data['end_date'] = $endDate;
        return $this->add($data);
    }

    public function editLimit($supplierId, $amount, $riskAmount, $zxTime, $diyaRate, $startDate, $endDate) {
        $where['supplier_id'] = $supplierId;
        $data['amount'] = $amount;
        $data['risk_amount'] = $riskAmount;
        $data['zx_time'] = $zxTime;
        $data['diya_rate'] = $diyaRate;
        $data['start_date'] = $startDate;
        $data['end_date'] = $endDate;
        return $this->where($where)->save($data);
    }

    public function delLimit($supplierId) {
        $where['supplier_id'] = $supplierId;
        return $this->where($where)->delete();
    }

}

?>
