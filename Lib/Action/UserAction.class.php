<?php

class UserAction extends CommonAction {

    protected $config = array(
        'need_login' => true,
    );

    protected $userModel;

    function __construct() {
        parent::__construct();
        $this->userModel = D('User');
    }

    public function index() {
        $this->display();
    }

    public function searchUser() {
        $username = I('username');
        $enterpriseName = I('enterprise_name');
        $role = I('role');
        $rs = $this->userModel->searchUser($username, $enterpriseName, $role);
        $this->ajaxReturn($rs, 'JSON');
    }

    public function addUser() {
        $username = I('username');
        $password = I('password');
        $enterpriseName = I('enterprise_name');
        $organizationCode = I('organization_code');
        $contactName = I('contact_name');
        $contactTel = I('contact_tel');
        $industry = I('industry');
        $role = I('role');
        $rs = $this->userModel->addUser($username, $password, $enterpriseName, $organizationCode, $contactName, $contactTel, $industry, $role);
        $this->ajaxReturn($rs, 'JSON');
    }

    public function editUser() {
        $username = I('username');
        $password = I('password');
        $enterpriseName = I('enterprise_name');
        $organizationCode = I('organization_code');
        $contactName = I('contact_name');
        $contactTel = I('contact_tel');
        $industry = I('industry');
        $role = I('role');
        $id = I('id');
        $rs = $this->userModel->editUser($id, $username, $password, $enterpriseName, $organizationCode, $contactName, $contactTel, $industry, $role);
        $this->ajaxReturn($rs, 'JSON');
    }

    public function delUser() {
        $id = I('id');
        $rs = $this->userModel->delUser($id);
        $this->ajaxReturn($rs, 'JSON');
    }

    public function password() {
        $this->display();
    }

    public function changePassword() {
        $oldPassword = I('oldPassword');
        $password = I('password');
        $userId = session('user')['ID'];
        $rs = $this->userModel->changePassword($userId, $oldPassword, $password);
        $this->ajaxReturn($rs, 'JSON');
    }

}

?>