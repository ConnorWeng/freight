<?php

class IndexAction extends Action {

    protected $config = array(
        'need_login' => false,
    );

    public function index(){
        $this->assign(array(
            'basepath' => str_replace('index.php', 'Public', __APP__)
        ));
        $this->display();
    }

    public function login() {
        $username = I('username');
        $password = I('password');

        $userModel = D('User');
        $user = $userModel->validate($username, $password);
        if ($user) {
            session('user', $user);
            U('Home/index', '', true, true, true);
        } else {
            $this->error('登录失败', U('Index/index'));
        }
    }

    public function logout() {
        session(null);
        U('Index/index', '', false, true);
    }

}