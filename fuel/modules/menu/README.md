# Fuel LbMenu Module

This module is used to manage the Lb Fuel Menu Package with Db Driver
You can install the Fuel LbMenu Menu package here : [Click here](https://github.com/jhuriez/fuel-lbMenu-package)

You may have bugs on this module, it's not completely finished. Furthermore, it's difficult to automatically adapted a module on an existing project.
This module can  be used as a basis to create your own section to manage the Fuel LbMenu Package with Db Driver.

# Features

* Manage all menu/item/submenu easily
* Set the parent of a menu with Dynatree js
* Add/Remove route params
* Set EAV attributes
* Multi language edition
* Breadcrumb menu
* Easy integration in your Theme
* Use jQuery and Bootstrap 3

# Installation

1. This module uses Theme class, you must create your theme folder.
2. Clone or download the fuel-module-menu repository
3. Move it into your module folder, and rename it to "menu"
4. Open your oil console
5. Run `oil refine menu::menu:install [your_public_folder] [your_theme]` to generate needed files (copy js and css files in assets folder). 
* [your_public_folder] is optionnally if your public folder is not named "public"
* [your_theme] is if you use a theme other than the default theme

# Configuration

## Base controller

This module is not securised, i've not added a ACL or Auth security. You need to attach this module on your base admin controller :

In `modules/menu/classes/controller/backend.php` at line 5 :

```php
  class Controller_Backend extends \Controller_Base_Backend
```

## Theme

It uses the Theme class from FuelPHP, consequently you need to have a theme for your administration.

## Implementation

All variables used in the template file from theme :

* $pageTitle : For the title of the page in any action
* $partials['content'] : The partial for the content
* `<?= \Theme::instance()->asset->render('css_plugin'); ?>` in the head
* `<?= \Theme::instance()->asset->render('js_core'); ?>` in the head
* `<?= \Theme::instance()->asset->render('js_plugin'); ?>` in the footer
* Your need to load jQuery and jQuery UI, and optionnaly Twitter Bootstrap v3

You can see an example of template here : [`menu/example/template.php`](https://github.com/jhuriez/fuel-module-menu/blob/master/example/template.php)

## Config file

file menu.php in `app/config` :

```php
return array(
	...,
	'module' => array(
		'use_casset' => false, // If you use Casset instead of Asset
		'force_jquery' => false, // Load jQuery library
		'force_bootstrap' => false, // Load Bootstrap library (js and css)
		'force_font-awesome' => false, // Load FontAwesome library
		'assets' => array(
			'css_plugin' => 'css', // Set the asset group "css" instead of "css_plugin",
		),
	),
	...,
);
```

## Change assets groups name

In your theme you don't want to use the asset group "css_plugin", but just "css" ? No problem, you can change it in the config file !

## External library

The module load automatically 2 js libraries :

* dynatree => For generate a tree ui
* bootbox => For modal confirmation

### jQuery

The module need jQuery et jQuery UI external libraries. If you have already these libraries in your theme, it's good.

But if you want to force to load the jquery library, you need to set "force_jquery" at true in the menu config file.

### Bootstrap & FontAwesome

This is the same for Bootstrap and Fontawesome librairies 

# Usage

Access the backoffice at : http://your-fuel-url/menu/backend

# Error

- Fuel\Core\ThemeException [ Error ]: Theme "default" could not be found.
It's because this module uses Themes for better flexibility. You must create a theme folder, by default it's DOCROOT/themes/default.

- ErrorException [ Fatal Error ]: Class 'Controller_Base_Backend' not found.
It's because the controller \Menu\Controller_Backend need to extends your admin controller in your project. In my case, the admin controller is named \Controller_Base_Backend

# Override Theme

Views module use Twitter bootstrap 3 tags for the UI. And FontAwesome

You can override them easily. For example for override the view 'menu/views/backend/index.php', you need to create the same file here "DOCROOT/themes/[your_theme]/menu/backend/index.php"

# Screenshots

1. [Manage menus root](http://i.imgur.com/sO06Y6R.png)
2. [Manage menu backend](http://i.imgur.com/4BGDyPE.png)
3. [Edit module menu](http://i.imgur.com/lav0mPL.png)