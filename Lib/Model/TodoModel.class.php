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

}

?>