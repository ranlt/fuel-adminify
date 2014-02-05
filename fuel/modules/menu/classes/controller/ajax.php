<?php

namespace Menu;

class Controller_Ajax extends \Fuel\Core\Controller_Rest
{

    public function post_EntriesByCategory($catid=0)
    {
        $catid  = \Input::post('catid');
        $menu_entries = $this->action_getMenu($catid);
        $this->response($menu_entries);
    }
    
    public function action_getMenu($catid, $parent=0, $level=0, $init=true, &$menu_entries=array())
    {
        
        if($init==true)
        {
            $menu_entries[0] = array('id' => 0, 'displayname' => "none");
        }
        
        if($catid == null || $catid == false)
        {
            return $menu_entries;
        }
        
        $current_level = \Menu\Model_Menu::get_Menu($catid, $parent);
        
        foreach($current_level as $entry) 
        { 
            $menu_entries[] =  array('id' => $entry['id'], 'displayname' => ($entry['divider'] != true) ? str_repeat('&nbsp;&nbsp;&nbsp;',$level).$entry['title'] :  str_repeat('&nbsp;&nbsp;&nbsp;',$level)."-----------");
            $this->action_getMenu($catid, $entry['id'] , $level+1, false, $menu_entries);                 
        } 
        
        return $menu_entries;
    }
}
?>