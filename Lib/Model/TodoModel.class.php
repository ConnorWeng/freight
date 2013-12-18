<?php

class TodoModel extends Model {

    protected $fields = array('id', 'name', 'content', 'user_id', 'end_date', 'priority', 'status', 'sort');

    public function addTodo($name, $content, $userId, $endDate, $priority, $status, $sort) {
        $maxId = intval($this->max('id'));
        $id = $maxId + 1;

        $data['id'] = $id;
        $data['name'] = $name;
        $data['content'] = $content;
        $data['user_id'] = $userId;
        $data['end_date'] = $endDate;
        $data['priority'] = $priority;
        $data['status'] = $status;
        $data['sort'] = $sort;

        return $this->add($data);
    }

    public function queryUncompletedTodo($userId) {
        $where['user_id'] = $userId;
        $where['status'] = array('in', '0,1');
        return $this->where($where)->select();
    }

    public function queryCompletedTodo($userId) {
        $where['user_id'] = $userId;
        $where['status'] = 2;
        return $this->where($where)->select();
    }

    public function queryTodoViaId($id) {
        $where['id'] = $id;
        return $this->where($where)->select();
    }

    public function readTodo($id) {
        $where['id'] = $id;
        $where['status'] = 0;
        return $this->where($where)->setField('status', 1);
    }

    public function completeTodo($id) {
        $where['id'] = $id;
        return $this->where($where)->setField('status', 2);
    }

}

?>