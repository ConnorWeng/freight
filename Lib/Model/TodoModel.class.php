<?php

class TodoModel extends Model {

    protected $fields = array('id', 'name', 'content', 'user_id', 'end_date', 'priority', 'status', 'sort');

    public function addTodo($targetId, $targetType) {

    }

}

?>