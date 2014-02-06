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

    public function queryEnterprises($type, $term) {
        $sql = "select enterprise_name from freight_user where usertype like '%$type%' and enterprise_name like '%$term%'";
        return $this->db->query($sql);
    }

    public function searchUser($username, $enterpriseName) {
        $sql = "select * from freight_user where username like '%$username%' and enterprise_name like '%$enterpriseName%'";
        return $this->db->query($sql);
    }

    public function addUser($username, $password, $enterpriseName, $organizationCode, $contactName, $contactTel, $industry) {
        $now = date('m/d/Y');
        $sql = "insert into freight_user(id, username, password, create_date, enterprise_name, organization_code, contact_name, contact_tel, industry) values (freight_user_seq.nextval, '$username', '$password', '$now', '$enterpriseName', '$organizationCode', '$contactName', '$contactTel', '$industry')";
        return $this->db->query($sql);
    }

    public function editUser($id, $username, $password, $enterpriseName, $organizationCode, $contactName, $contactTel, $industry) {
        $where['id'] = $id;
        $data['username'] = $username;
        $data['password'] = $password;
        $data['enterprise_name'] = $enterpriseName;
        $data['organization_code'] = $organizationCode;
        $data['contact_name'] = $contactName;
        $data['contact_tel'] = $contactTel;
        $data['industry'] = $industry;
        return $this->where($where)->save($data);
    }

    public function delUser($id) {
        $where['id'] = $id;
        return $this->where($where)->delete();
    }

}

?>