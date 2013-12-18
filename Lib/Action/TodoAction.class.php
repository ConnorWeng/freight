<?php

class TodoAction extends CommonAction {

    protected $todoModel;

    function __construct() {
        parent::__construct();
        $this->todoModel = D('Todo');
    }

    protected $config = array(
        'need_login' => true,
    );

    public function index() {
        $uncompletedTodoList = $this->todoModel->queryUncompletedTodo(session('user')['ID']);
        $completedTodoList = $this->todoModel->queryCompletedTodo(session('user')['ID']);
        $this->assign(array(
            'list' => $uncompletedTodoList,
            'list2' => $completedTodoList,
        ));
        $this->display();
    }

    public function detail() {
        $id = I('id');
        $this->todoModel->readTodo($id);
        $rs = $this->todoModel->queryTodoViaId($id);
        if (count($rs) > 0) {
            $vo = $rs[0];
            $vo['CONTENT'] = 'http://'.$this->_server('HTTP_HOST').$vo['CONTENT'];
        }
        $this->assign(array(
            'vo' => $vo,
        ));
        $this->display();
    }

}

?>