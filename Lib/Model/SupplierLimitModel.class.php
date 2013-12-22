<?php

class SupplierLimitModel extends Model {

    protected $fields = array('supplier_id', 'sx_limit', 'ck_limit', 'zx_mgt_days');

    public function isExpire($lfSupplier, $kpDate) {
        $sql  = " select floor(sysdate - to_date('".$kpDate."', 'mm/dd/yyyy')) -";
        $sql .= "   nvl((select l.zx_mgt_days";
        $sql .= "     from ".C('DB_PREFIX')."supplier_limit l, ".C('DB_PREFIX')."user u";
        $sql .= "   where l.supplier_id = u.id";
        $sql .= "     and u.enterprise_name = '".$lfSupplier."'),0) delta from dual";

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

    public function getZxMgtDays($supplier) {
        $sql = "select * from freight_supplier_limit l, freight_user u where l.supplier_id = u.id and u.enterprise_name = '$supplier'";
        $rs = $this->db->query($sql);
        if (count($rs) > 0) {
            return $rs[0]['ZX_MGT_DAYS'];
        } else {
            return $rs;
        }
    }

}

?>
