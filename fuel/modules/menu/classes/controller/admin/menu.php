<?php

namespace Menu;

class Controller_Admin_Menu extends \Controller_Base_Admin
{

    public function before() {
        if (\Input::is_ajax())
        {
            return parent::before();
        }
        else
        {
            parent::before();
        }

        // Load Config
        \Config::load('menu', true);
        
        // Load language
        \Lang::load('menu', true);

        // Message class exist ?
        //$this->use_message = class_exists('\Messages');

        // Use Casset ?
        //$this->use_casset = \Config::get('menu.module.use_casset');

        // Set Media
        //$this->setModuleMedia();
    }

    /**
     * List all menu
     */
    public function action_index()
    {

        $id = $this->param('id');

        if ($id == null)
        {
            // In root menu
            $this->data['menus'] = $menus = \LbMenu\Model_Menu::forge()->roots()->get();
            $this->data['menuBreadcrumb'] = '';
            $this->theme->get_partial('page_header', 'partials/page_header')->set('title', __('menu.title.manage_all'));
            $this->theme->get_template()->set('title', __('menu.title.manage_all'));
        }
        else
        {
            // In sub menu
            $this->data['menuParent'] = $menuParent = \LbMenu\Model_Menu::find($id);
            $this->data['menus'] = $menus = $menuParent->children()->get();
            $menuParentLang = \LbMenu\Helper_Menu::getLang($menuParent);
            $this->data['menuBreadcrumb'] = \Menu\Helper_Menu::generateBreadcrumb($menuParent);
            $this->theme->get_partial('page_header', 'partials/page_header')->set('title', __('menu.title.manage', array('name' => $menuParentLang->text)));
            $this->theme->get_template()->set('title', __('menu.title.manage', array('name' => $menuParentLang->text)));
        }

        // Count link and submenu
        foreach((array)$menus as $k => $menu)
        {
            $nbLink = 0;
            $nbSub = 0;

            $childs = $menu->children()->get();
            foreach((array)$childs as $child)
            {
                if ($child->has_children())
                {
                    $nbSub++;
                }
                else
                {
                    $nbLink++;
                }
            }

            $menus[$k]->nbLink = $nbLink;
            $menus[$k]->nbSub = $nbSub;
        }

        

        return $this->theme
                ->get_template()
                ->set(  'content', 
                        \Theme::instance()->view('admin/index', $this->data, null, false)
                    );     

        //$this->theme->set_partial('content', 'admin/index')->set($this->data, null, false);
    }    

    /**
     * Delete a menu
     */
    public function action_delete()
    {
        $menu = \LbMenu\Model_Menu::find($this->param('id'));
        if (\LbMenu\Helper_Menu::delete($menu))
        {
            $this->use_message and \Messages::success(__('menu.message.deleted'));
        }
        else
        {
            $this->use_message and \Messages::error(__('menu.error'));
        }

        \Response::redirect_back(\Router::get('menu_backend_menu'));
    }

    /**
     * Add or edit a menu
     */
    public function action_add()
    {
        // Get id menu and id parent if exist
        $id       = $this->param('id');
        $parentId = $this->param('parent');

        // Set some data to view
        $this->data['parent_id'] = ($parentId !== null) ? $parentId : 'none';
        $this->data['isUpdate']  = $isUpdate = ($id !== null) ? true : false;
        
        // Get languages
        $this->data['language'] = $language = \Config::get('language');

        // Forge Menu fieldset
        $form = \Fieldset::forge('menuForm', array('form_attributes' => array('class' => 'form-horizontal')));
        $form->add_model('LbMenu\\Model_Menu');
        $form->add('add', '', array('type' => 'submit', 'value' => ($isUpdate) ? __('menu.edit')
                        : __('menu.add'), 'class' => 'btn btn-primary'));

        // Get menu object
        $this->data['menu'] = $menu = ($isUpdate) ? \LbMenu\Model_Menu::find($id) : new \LbMenu\Model_Menu();
        $form->populate($menu);

        // Forge MenuLang fieldset
        $formLang = \Fieldset::forge('menuFormLang');
        $formLang->add_model('LbMenu\\Model_Lang');

        // Get menuLang object
        $this->data['menuLang'] = $menuLang = \LbMenu\Helper_Menu::getLang($menu, $language);
        $formLang->populate($menuLang);

        // Set params route
        $params = $menu->named_params;
        $this->data['params'] = array();
        foreach((array)$params as $name => $value)
        {
            $this->data['params'][] = array('name' => $name, 'value' => $value);
        }

        // Set EAV
        $eavArr = \Menu\Helper_Menu::getEav($menu, false, $isUpdate, $parentId);
        $this->data['eav'] = $eavArr['eav'];
        $this->data['themeName'] = $eavArr['theme_name'];

        // Page title
        if ($isUpdate)
        {
            //$this->theme->get_template()->set('pageTitle', __('menu.title.edit_menu', array('name' => $menuLang->text)));
            $this->theme->get_partial('page_header', 'partials/page_header')->set('title', __('menu.title.edit_menu', array('name' => $menuLang->text)));
            $this->theme->get_template()->set('title', __('menu.title.edit_menu', array('name' => $menuLang->text)));

            // If menu has parent
            if ($menu->is_child())
            {
                $parentMenu = $menu->parent()->get_one();
                $this->data['parent_id'] = $parentId = $parentMenu->id;
            }
        }
        else
        {
            //$this->theme->get_template()->set('pageTitle', __('menu.title.add_menu'));
            $this->theme->get_partial('page_header', 'partials/page_header')->set('title', __('menu.title.add_menu'));
            $this->theme->get_template()->set('title', __('menu.title.add_menu'));
        }

        // Form process
        if (\Input::post('add'))
        {
            // validate the input
            $form->validation()->run();
            $formLang->validation()->run();

            // if validated, create the object
            if (!$form->validation()->error() && !$formLang->validation()->error())
            {
                // Get params
                $params = array();
                foreach((array)\Input::post('params') as $param)
                {
                    $params[$param['name']] = $param['value'];
                }

                // Parent Id logic
                if ($parentId != 'none' && \Input::post('activeNode') !== null && \Input::post('activeNode') != 'none' && \Input::post('activeNode'))
                {
                    // If not change parent
                    if ($isUpdate === false || \Input::post('activeNode') != $menu->parent()->get_one()->id)
                    {
                        $parentId = \Input::post('activeNode');
                        $changeParentId = \Input::post('activeNode');
                    }
                    else
                    {
                        $changeParentId = false;
                    }
                }
                else
                {
                    $changeParentId = false;
                }

                // Set params for save the menu
                $params = array(
                    'slug' => \Inflector::friendly_title($form->validated('slug')),
                    'link' => $form->validated('link'),
                    'is_blank' => $form->validated('is_blank'),
                    'theme' => $form->validated('theme'),
                    'use_router' => $form->validated('use_router'),
                    'named_params' => $params,
                    'text' => $formLang->validated('text'),
                    'title' => $formLang->validated('title'),
                    'small_desc' => $formLang->validated('small_desc'),
                    'language' => $formLang->validated('language'),
                    'eav' => (\Input::post('eav')) ? : array(),
                    'parent_id' => $changeParentId,
                );
                
                // Save
                $saveArr = \LbMenu\Helper_Menu::manage($menu, $params, true);
                if ($saveArr['response'])
                {
                    if ($isUpdate)
                        $this->use_message and \Messages::success(__('menu.message.edited'));
                    else
                        $this->use_message and \Messages::success(__('menu.message.added'));

                    $redirectLink = ($parentId == 'none' || !$parentId) ? \Router::get('menu_backend_menu') : 
                                                                          \Router::get('menu_backend_submenu', array('id' => $parentId));
                    \Response::redirect_back($redirectLink);
                } else
                {
                    $this->use_message and \Messages::error(__('menu.error'));
                }
            }
            else
            {
                foreach ($form->validation()->error() as $error)
                {
                    $this->use_message and \Messages::error($error);
                }
            }
        }
        $form->repopulate();
        $formLang->repopulate();

        $this->data['form'] = $form;
        $this->data['formLang'] = $formLang;

        return $this->theme
                ->get_template()
                ->set(  'content', 
                        \Theme::instance()->view('admin/add', $this->data, null, false)
                    );  
        //$this->theme->set_partial('content', 'backend/add')->set($this->data, null, false);
    }

    /**
     * REST API for menu
     */
    public function action_api($context = '')
    {
        \Package::load('lbMenu');
        switch ($context)
        {
            /**
             * Show menus in Dynatree
             */
            case 'show_menus':
                $json = \Menu\Helper_Menu::getMenuTree(\Input::get('idSelect'), \Input::get('show_none'), \Input::get('idMenu'));
                return $this->response($json);
                break;
            /**
             * Get the menu language data
             */
            case 'get_menu_lang':
                $idMenu = \Input::get('id');

                $menu = \LbMenu\Model_Menu::find($idMenu);
                $menu or $menu = new \LbMenu\Model_Menu();
                
                $lang = \Input::get('lang');

                $menuLang = \LbMenu\Helper_Menu::getLang($menu, $lang);
                return $this->response(array('data' => $menuLang->to_array(), 'success' => true));

                break;
            /**
             * Move menu position
             */
            case 'move_menu':
                $menuCurrent = \LbMenu\Model_Menu::find(\Input::get('idCurrent'));
                
                if (\Input::get('idPrev') !== null)
                {
                    $menuPrev = \LbMenu\Model_Menu::find(\Input::get('idPrev'));
                    $menuCurrent->sibling($menuPrev)->save();
                }
                else if (\Input::get('idNext') !== null)
                {
                    $menuNext = \LbMenu\Model_Menu::find(\Input::get('idNext'));
                    $menuCurrent->previous_sibling($menuNext)->save();
                }
                
                $json = array('message' => 'success');
                return $this->response($json);
                break;
            /**
             * Get EAV of a menu
             */
            case 'get_eav':
                $theme = \Input::get('theme');
                $isUpdate = \Input::get('isUpdate');
                $idMenu = \Input::get('idMenu');
                $idRoot = \Input::get('idRoot');

                $eav = \Menu\Helper_Menu::getEav($idMenu, $theme, $isUpdate, false, $idRoot);

                return $this->response(array('data' => $eav['eav'], 'theme_name' => $eav['theme_name'], 'success' => true));
            break;
        }
    }

}