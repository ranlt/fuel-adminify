<?php

namespace LbMenu;

class Model_Menu extends \Orm\Model_Nestedset
{

    protected static $_properties = array(
        'id',
        'left_id' => array(
            'form' => array('type' => false),
        ),
        'right_id' => array(
            'form' => array('type' => false),
        ),
        'tree_id' => array(
            'form' => array('type' => false),
        ),
        'slug' => array(
            'label' => 'menu_model_menu.slug',
            'default' => '',
            'null' => false,
            'validation' => array('required'),
        ),
        'link' => array(
            'label' => 'menu_model_menu.link',
            'default' => '',
        ),
        'use_router' => array(
            'label' => 'menu_model_menu.use_router',
            'form' => array('type' => 'select', 'value' => '0', 'options' => array('0' => 'menu_model_menu.no', '1' => 'menu_model_menu.yes')),
        ),
        'named_params' => array(
            'label' => 'menu_model_menu.named_params',
        ),
        'is_blank' => array(
            'label' => 'menu_model_menu.is_blank',
            'null' => false,
            'default' => false,
            'form' => array('type' => 'select', 'value' => '0', 'options' => array('0' => 'menu_model_menu.no', '1' => 'menu_model_menu.yes')),
        ),
        'theme' => array(
            'label' => 'menu_model_menu.theme',
            'form' => array('type' => 'select'),
        ),
        'created_at' => array(
            'form' => array('type' => false),
        ),
        'updated_at' => array(
            'form' => array('type' => false),
        ),
    );
    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'events' => array('before_insert'),
            'mysql_timestamp' => false,
        ),
        'Orm\Observer_UpdatedAt' => array(
            'events' => array('before_update'),
            'mysql_timestamp' => false,
        ),
        'LbMenu\\Observer_Serialize' => array(
            'events' => array('before_save', 'after_load'),
            'source' => 'named_params',
        ),
    );
    
    protected static $_table_name = 'menu_menu';
    
    protected static $_tree = array(
        'left_field' => 'left_id',
        'right_field' => 'right_id',
        'tree_field' => 'tree_id',
        'title_field' => 'slug',
    );

    protected static $_has_many = array(
        'menu_langs' => array(
            'key_from' => 'id',
            'model_to' => 'LbMenu\Model_Lang',
            'key_to' => 'id_menu',
            'cascade_save' => true,
            'cascade_delete' => true,
        ),
        'menu_attributes' => array(
            'key_from' => 'id',   
            'model_to' => 'LbMenu\Model_Attribute',  
            'key_to' => 'id_menu',
            'cascade_save' => true, 
            'cascade_delete' => true,
        ),
    );

    protected static $_eav = array(
        'menu_attributes' => array(   
            'attribute' => 'key',
            'value' => 'value',  
        )
    );

    public static function _init()
    {
        \Lang::load('menu_model_menu', true);
    }


    public static function set_form_fields($form, $instance = null)
    {
        // set theme options
        \Config::load('menu', true);
        $themes = array_keys(\Config::get('menu.themes'));
        $themeDefault = \Config::get('menu.theme_default');
        $themeFallback = \Config::get('menu.theme_fallback');

        // Call parent for create the fieldset and set default value
        parent::set_form_fields($form, $instance);

        foreach($themes as $theme)
            $form->field('theme')->set_options($theme, $theme);

        if (in_array($themeDefault, $themes))
        {
            $form->field('theme')->set_value($themeDefault);
        }
        else if(in_array($themeFallback, $themes))
        {
            $form->field('theme')->set_value($themeFallback);
        }
    }    
}
