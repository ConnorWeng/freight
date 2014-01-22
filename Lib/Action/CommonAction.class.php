<?php

class CommonAction extends Action {

    protected function _initialize() {
        $menu = D('Menu');
        $tree = list_to_tree($menu->getMenuList());
        $this->assign(array(
            'basepath' => str_replace('index.php', 'Public', __APP__),
            'tree' => $tree,
            'js_file' => './Tpl/'.MODULE_NAME.'/js/'.ACTION_NAME.'.html',
        ));
    }

    public function queryEnterprises() {
        $type = I('type');
        $term = I('term');
        $userModel = D('User');
        $rs = $userModel->queryEnterprises($type, $term);
        $result = array();
        foreach ($rs as $row) {
            array_push($result, $row['ENTERPRISE_NAME']);
        }
        $this->ajaxReturn($result, 'JSON');
    }

}

?>