<?php

namespace LbMenu;

class Model_Lang extends \Orm\Model
{
    protected static $_properties = array(
        'id',
        'id_menu',
        'title' => array(
            'label' => 'menu_model_lang.title',
        ),
        'text' => array(
            'label' => 'menu_model_lang.text',
            'null' => false,
            'validation' => array('required'),
        ),
        'small_desc' => array(
            'label' => 'menu_model_lang.small_desc',
        ),
        'language' => array(
            'label' => 'menu_model_lang.language',
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
    );
    protected static $_table_name = 'menu_lang';

    protected static $_belongs_to = array(
        'menu' => array(
            'key_from' => 'id_menu',
            'model_to' => 'LbMenu\Model_Menu',
            'key_to' => 'id',
            'cascade_save' => false,
            'cascade_delete' => false,
        ),
    );

    public static function _init()
    {
        \Lang::load('menu_model_lang', true);
    }

    public static function set_form_fields($form, $instance = null)
    {
        // add language options
        $languagesTmp = array();
        foreach((array)\Config::get('languages') as $language)
        {
            $languagesTmp[$language] = ($language == \Config::get('language')) ? $language . ' (default)' : $language;
        }

        // If no supported_languages config, we add the language default
        in_array(\Config::get('language'), $languagesTmp) or $languagesTmp[\Config::get('language')] = \Config::get('language') . ' (default)';

        static::$_properties['language']['form']['options'] = $languagesTmp;

        parent::set_form_fields($form, $instance);
    }    
}
