<?php

class HomeAction extends CommonAction {

    protected $config = array(
        'need_login' => true,
    );

    public function index() {
        $this->display();
    }

}