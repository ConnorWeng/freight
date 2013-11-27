<?php

class MenuModel extends Model {

    public function getMenuList() {
        $userId = getUserId();
        $rs = null;
        if ($userId != null) {
            $sql = ' select m.id, m.pid, m.name, m.url, m.icon, m.remark';
            $sql .= ' from '.C('DB_PREFIX').'menu m, '.C('DB_PREFIX').'role_menu rm, '.C('DB_PREFIX').'role_user ru';
            $sql .= ' where ru.user_id = 1 and ru.role_id = rm.role_id and rm.menu_id = m.id';
            $sql .= ' order by m.sort';
            $rs = $this->db->query($sql);
        }
        return $rs;
    }

}

?>