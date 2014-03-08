<?php
    $data['categories'] = $categories;
    $data['parent_links'] = $parent_links;
    echo render('admin/menu/_form', $data); 
?>

            