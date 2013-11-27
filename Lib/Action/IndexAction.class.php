<?php

class IndexAction extends Action {

    public function index(){
        $this->assign(array(
            'basepath' => str_replace('index.php', 'Public', __APP__)
        ));
        $this->display();
    }

    public function login() {
        $username = I('username');
        $password = I('password');

        $user = D('User');
        $userId = $user->validate($username, $password);
        if ($userId != 0) {
            session('user_id', $userId);
            U('Home/index', '', true, true, true);
        } else {
            U('Index/index', '', true, true, true);
        }
    }

}