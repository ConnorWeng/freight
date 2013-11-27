<?php

class CommonAction extends Action {

    protected function defaultDisplay() {
        $menu = D('Menu');
        $tree = list_to_tree($menu->getMenuList());
        $this->assign(array(
            'basepath' => str_replace('index.php', 'Public', __APP__),
            'tree' => $tree,
        ));
        $this->display();
    }

}

?>