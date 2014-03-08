<?php
    $data['categories']     = $categories;
    $data['parent_links']   = $parent_links;
    $data['menu']           = $menu;
    echo render('admin/menu/_form', $data); 
?>