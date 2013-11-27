<?php
class NavWidget extends Widget {
        public function render($data) {
                $tree = $data['tree'];
                //dump($tree);
                return $this -> tree_nav($tree);
        }

        function tree_nav($tree, $level = 0) {
                $level++;
                $html = "";
                //dump($tree);
                if (is_array($tree)){
                        if ($level >1) {
                                $html = "<ul class='submenu'>\r\n";
                        } else {
                                $html = "<ul class='nav nav-list'>\r\n";
                        }
                        foreach ($tree as $val){
                                if (isset($val["NAME"])) {
                                        $title = $val["NAME"];
                                        if (!empty($val["URL"])) {
                                                $url = U($val['URL']);
                                        } else {
                                                $url = "#";
                                        }
                                        if (empty($val["ID"])) {
                                                $id = $val["NAME"];
                                        }else{
                                                $id = $val["ID"];
                                        }

                                        if(empty($val['ICON'])){
                                                $icon="icon-angle-right";
                                        }else{
                                                $icon=$val['ICON'];
                                        }
                                        if (isset($val['_CHILD'])) {
                                                $html .= "<li>\r\n";
                                                $html .= "<a class=\"dropdown-toggle\" node=\"$id\" href=\"" . "$url\">";
                                                $html .= "<i class=\"$icon\"></i>";
                                                $html .= "<span class=\"menu-text\">$title</span>";
                                                $html .= "<b class=\"arrow icon-angle-down\"></b>";
                                                $html .= "</a>\r\n";
                                                $html .= $this->tree_nav($val['_CHILD'], $level);
                                                $html = $html . "</li>\r\n";
                                        } else {
                                                $html .="<li>\r\n";
                                                $html .="<a  node=\"$id\" href=\"" . "$url\">\r\n";
                                                $html .= "<i class=\"$icon\"></i>";
                                                $html .= "<span class=\"menu-text\">$title</span>";
                                                $html .="</a>\r\n</li>\r\n";
                                        }
                                }
                        }
                        $html = $html . "</ul>\r\n";
                }
                return $html;
        }
}
?>