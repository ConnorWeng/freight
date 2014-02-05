<?php

class TodoModel extends Model {

    protected $fields = array('id', 'name', 'content', 'user_id', 'end_date', 'priority', 'status', 'sort');

    public function addTodo($name, $content, $roleId, $endDate, $priority, $status, $sort) {
        $sql = "insert into freight_todo " .
               "  select freight_todo_seq.nextval, " .
               "         '$name', " .
               "         '$content', " .
               "         user_id, " .
               "         null, " .
               "         null, " .
               "         $status, " .
               "         null " .
               "    from freight_role_user " .
               "   where role_id = $roleId";

        return $this->db->query($sql);
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