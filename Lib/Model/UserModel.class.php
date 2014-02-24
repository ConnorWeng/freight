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

    public function searchUser($username, $enterpriseName, $role) {
        $sql = "select u.*, ru.role_id role from freight_user u, freight_role_user ru where u.id = ru.user_id and u.username like '%$username%' and u.enterprise_name like '%$enterpriseName%' and ru.role_id like '%$role%'";
        return $this->db->query($sql);
    }

    public function addUser($username, $password, $enterpriseName, $organizationCode, $contactName, $contactTel, $industry, $role) {
        $now = date('m/d/Y');
        $sql = "declare retcode varchar2(10); msg varchar2(100); begin pckg_freight_user.add_user('$username', '$password', '$now', '$enterpriseName', '$organizationCode', '$contactName', '$contactTel', '$industry', '$role', retcode, msg); end;";
        return $this->db->query($sql);
    }

    public function editUser($id, $username, $password, $enterpriseName, $organizationCode, $contactName, $contactTel, $industry, $role) {
        $sql = "declare retcode varchar2(10); msg varchar2(100); begin pckg_freight_user.edit_user('$id', '$username', '$password', '$now', '$enterpriseName', '$organizationCode', '$contactName', '$contactTel', '$industry', '$role', retcode, msg); end;";
        return $this->db->query($sql);
    }

    public function delUser($id) {
        $where['id'] = $id;
        return $this->where($where)->delete();
    }

    public function changePassword($id, $oldPassword, $password) {
        $where['id'] = $id;
        $where['password'] = $oldPassword;
        $data['password'] = $password;
        return $this->where($where)->save($data);
    }

}

?>