<?php
/**
 * Part of the Fuel framework.
 *
 * @package    Fuel
 * @version    1.6
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2013 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * NOTICE:
 *
 * If you need to make modifications to the default configuration, copy
 * this file to your app/config folder, and make them in there.
 *
 * This will allow you to upgrade fuel without losing your custom config.
 */


return array(
	// regular form definitions
	'prep_value'                 => true,
	'auto_id'                    => true,
	'auto_id_prefix'             => 'form_',
	'form_method'                => 'post',
	'form_template'              => "\n{open}\n{fields}\n{close}\n",
    
	'field_template'             => "<div class=\"{error_class} form-group\">
            \n{label}{required}
            \n<div class=\"col-lg-10\">
               \n{field} <span>{description}</span> {error_msg}
            \n</div></div>",
    
	'multi_field_template'       => "<div class=\"{error_class} form-group\">
            \n{group_label}{required}
            \n<div class=\"col-lg-10\">
            \n{fields}{field} {label}<br />\n{fields}<span>{description}</span>{error_msg}
            \n</div></div>",
	'error_template'             => '<span>{error_msg}</span>',
	'group_label'	             => '{label}',
	'required_mark'              => '',
	'inline_errors'              => false,
	'error_class'                => null,
	'label_class'                => 'control-label col-lg-2',

	// tabular form definitions
	'tabular_form_template'      => "<table>{fields}</table>\n",
	'tabular_field_template'     => "{field}",
	'tabular_row_template'       => "<tr>{fields}</tr>\n",
	'tabular_row_field_template' => "\t\t\t<td>{label}{required}&nbsp;{field} {error_msg}</td>\n",
	'tabular_delete_label'       => "Delete?",
);
