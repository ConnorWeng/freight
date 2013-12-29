<?php

class UserModel extends Model {

    protected $fields = array('id', 'username', 'password', 'usertype', 'create_date', 'enterprise_name', 'organization_code', 'contact_name', 'contact_tel', 'industry', 'business_nature', 'enterprise_create_date', 'enterprise_address', 'annual_turnover');

    public function validate($username, $password) {
        $where['username'] = $username;
        $where['password'] = $password;
        $rs = $this->where($where)->select();
        if (count($rs) > 0) {
            return $rs[0];
        } else {
            return null;
        }
    }

    public function queryIdByName($name) {
        $where['enterprise_name'] = $name;
        return $this->where($where)->find();
    }

}

?>