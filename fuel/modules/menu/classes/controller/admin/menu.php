<?php
/**
 * Part of Fuel Adminify.
 *
 * @package     fuel-adminify
 * @version     2.0
 * @author      Marcus Reinhardt - Pseudoagentur
 * @license     MIT License
 * @copyright   2014 Pseudoagentur
 * @link        http://www.pseudoagentur.de
 * @github      https://github.com/Pseudoagentur/fuel-adminify
 */

namespace Menu;

class Controller_Admin_Menu extends \Controller_Base_Admin
{

    public function before()
    {
        parent::before();

        \Theme::instance()->set_partial('subnavigation', 'partials/subnavigation');
/*        \Theme::instance()->set_partial('subnavigation', 'partials/subnavigation');

        if(!\Warden::can(array('read'), 'users'))
        {
            \Messages::warning('Ups. You have not the permission to do this action.');
            \Fuel\Core\Response::redirect('admin');
        }     */
    }

    public function action_index()
    {

        $categories         = \Menu\Model_Categories::find('all');
            
        foreach ($categories as $category)
        {
            
            $data['menues'][$category->id] = array(
                                                    'catid' => $category->id, 
                                                    'catname' => $category->catname, 
                                                    'alias' => $category->alias
                                                  );
            
            $data['menues'][$category->id]['menu'] = $this->action_getMenu($category->id, true);
        }

        return \Theme::instance()
                ->get_template()
                ->set(  'title', 'Manage Menues')
                ->set(  'content', 
                        \Theme::instance()->view('admin/menu/index', $data)
                    );
       

    }
	
    public function action_getMenu($catid, $isAdmin=false, $parent=0, $level=0, $init=true, &$menu_entries=array())
    {
        
        if($isAdmin == false)
        {
            if($init==true)
            {
                $menu_entries[0] = "none";
            }

            if($catid == null || $catid == false)
            {
                return $menu_entries;
            }

            $current_level = \Menu\Model_Menu::get_Menu($catid, $parent);

            foreach($current_level as $entry) 
            { 
                $menu_entries[$entry['id']] = str_repeat('&nbsp;&nbsp;&nbsp;',$level).$entry['title'];
                $this->action_getMenu($catid, $isAdmin, $entry['id'] , $level+1, false, $menu_entries); 
            } 

            return $menu_entries;
        }
        else
        {
            $current_level = \Menu\Model_Menu::get_Menu($catid, $parent);

            foreach($current_level as $entry) 
            { 

                $menu_entries[] =  array(
                                        'id'        => $entry['id'], 
                                        'name'      => str_repeat('&nbsp;&nbsp;&nbsp;',$level).$entry['title'],
                                        'link'      => $entry['link'],
                                        'divider'   => $entry['divider'],
                                        'position'  => $entry['position']
                                        );
                $this->action_getMenu($catid, $isAdmin, $entry['id'] , $level+1, false, $menu_entries); 
                 
            } 

            return $menu_entries;
        }
    }

    public function action_create($catid = null)
    {
        if (\Input::method() == 'POST')
        {
            $menu = Model_Menu::forge(array(
                    'title'     => \Input::post('title'),
                    'parent'    => \Input::post('parent'),
                    'position'  => \Input::post('position'),
                    'link'      => \Input::post('link'),
                    'catid'     => \Input::post('catid'),
                    'active'    => \Input::post('active', 0),
                    'divider'   => \Input::post('divider', 0),
                    'menuicon'  => \Input::post('menuicon', 'none')
            ));

            if ($menu && $menu->save())
            {
                \Messages::success('Added Menu entry #'.$menu->id.'.');
                \Response::redirect('admin/menu');
            }

            else
            {
                \Messages::error('Could not add menu entry.');
            }
        }


        $all_categories_obj         = \Menu\Model_Categories::find('all');

        foreach ($all_categories_obj as $i => $obj)
        {
            $all_categories_arr[$i] = $obj->to_array();
        }
        
        foreach ($all_categories_arr as $value) 
        {
            $all_categories[$value['id']] = $value['catname'];
        }

        $data['categories']  = $all_categories;
        $data['parent_links']   = $this->action_getMenu(($catid == null) ? key($all_categories) : $catid);
        
        return \Theme::instance()
                ->get_template()
                ->set(  'title', 'Create Menu')
                ->set(  'content', 
                        \Theme::instance()->view('admin/menu/create', $data)
                    );

    }
    
    public function action_edit($id = null)
    {
        $menu                   = \Menu\Model_Menu::find_by_id($id);

        if (\Input::method() == 'POST')
        {

            $menu->title        = \Input::post('title');
            $menu->parent       = \Input::post('parent');
            $menu->position     = \Input::post('position');
            $menu->link         = \Input::post('link');
            $menu->catid        = \Input::post('catid');
            $menu->active       = \Input::post('active');
            $menu->divider      = \Input::post('divider');
            $menu->menuicon     = \Input::post('menuicon', 'none');


            if ($menu->save())
            {
                \Messages::success('Updated menu #' . $id);

                \Response::redirect('admin/menu');
            }

            else
            {
                    \Messages::warning('Nothing updated.');
            }
        }

        $all_categories_obj     = \Menu\Model_Categories::find('all');
        foreach ($all_categories_obj as $i => $obj)
        {
            $all_categories_arr[$i] = $obj->to_array();
        }

        foreach ($all_categories_arr as $value) 
        {
            $all_categories[$value['id']] = $value['catname'];
        }

        $data['categories']     = $all_categories;
        $data['parent_links']   = $this->action_getMenu($menu->catid);

        $data['menu']           = $menu;

        return \Theme::instance()
                ->get_template()
                ->set(  'title', 'Edit Menu')
                ->set(  'content', 
                        \Theme::instance()->view('admin/menu/edit', $data)
                    );

    }

    public function action_delete($id = null)
    {
        $menu = \Menu\Model_Menu::find_by_id($id);

        if ($menu && $menu->delete())
        {
            \Messages::success('Deleted menu entry #'.$id);
        }
        else
        {
            \Messages::error('Could not delete menu entry #'.$id);
        }

        \Response::redirect('admin/menu');

    }
    
    public function action_create_category($catid = null)
    {
        if (\Input::method() == 'POST')
        {
            $category = \Menu\Model_Categories::forge(array(
                    'catname'   => \Input::post('catname'),
                    'alias'     => \Input::post('alias')
            ));

            if ($category && $category->save())
            {
                \Messages::success('Added Menu category entry #'.$category->id.'.');
                \Response::redirect('admin/menu');
            }

            else
            {
                \Messages::error('Could not add menu category entry.');
            }
        }

        return \Theme::instance()
                ->get_template()
                ->set(  'title', 'Create Menu Category')
                ->set(  'content', 
                        \Theme::instance()->view('admin/menu/create_category')
                    );

    }
    
    public function action_edit_category($id = null)
    {
        $category = \Menu\Model_Categories::find_by_id($id);

        if (\Input::method() == 'POST')
        {

            $category->catname      = \Input::post('catname');
            $category->alias        = \Input::post('alias');

            if ($category->save())
            {
                \Messages::success('Updated category #' . $id);    
                \Response::redirect('admin/menu');
            }

            else
            {
                \Messages::warning( 'Nothing updated.');
            }
        }
        $data['category'] = $category;

        return \Theme::instance()
                ->get_template()
                ->set(  'title', 'Edit Menu Category')
                ->set(  'content', 
                        \Theme::instance()->view('admin/menu/edit_category', $data)
                    );

    }

    public function action_delete_category($id = null)
    {
        $menu = \Menu\Model_Categories::find_by_id($id);

        if ($menu && $menu->delete())
        {
            \Messages::success('Deleted menu category entry #'.$id);
        }

        else
        {
            \Messages::error('error','Could not delete menu category entry #'.$id);
        }

        \Response::redirect('admin/menu');

    }
}