<?php

class AuthCheckBehavior extends Behavior {

    public function run(&$params) {
        $needLogin = $params['need_login'];
        if ($needLogin) {
            if (!session('?user')) {
                U('Index/index', '', true, true, false);
            }
        }
    }

}

?>