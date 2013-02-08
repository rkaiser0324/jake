jake
====

This is a Joomla 3.0 component that allows you to render a CakePHP 2.2 app inside Joomla.  The project was begun in 2007 (supporting Joomla 1.5 and CakePHP 1.2), 
and in 2012 I updated it to support Joomla 3.0 and CakePHP 2.2.

## Packaging for Joomla
Now that Joomla 3.0 offers on-demand "discovery" of new extensions directly from the file system, it's no longer necessary to package them up if you don't want to.  But if you do wish to package this for distribution, 
follow these steps:

1.  Copy the contents of this repo into the a temporary directory somewhere
- Move `administrator/components/com_jake/jake.xml` to `./` 
- Move `administrator/components/com_jake` to `admin/`
- Move `components/com_jake` to `site/`
- If you're not on Windows, zip it up via commandline.  Otherwise, multi-select `jake.xml`, `admin/`, and `site/`.  Right-click *Send to Compressed (zipped) folder* to zip it up and rename the resulting zipfile as desired
- In the Joomla Extension Manager, browse to the zipfile to install it.

The `.htaccess` file isn't packaged as part of the component; it contains the changes needed to support SEO-friendly CakePHP URLs.

## Configuration

1. In Joomla Administration, under Extensions -> Plug-in Manager, disable the "System - Highlighting" plugin as it conflicts with the Jake component.
- The CakePHP directory should be a sibling to the Joomla one, i.e.,

    `/path/to/www/joomla-cms` (your JOOMLA_ROOT)
    `/path/to/www/cakephp` (your CAKEPHP_ROOT)
- Add the following Apache Alias, used for delivering existing files from under `CAKEPHP_ROOT/app/webroot`, to the Joomla VirtualHost. This should point to the `app/webroot` directory of the CakePHP app.
```
Alias /webapp "CAKEPHP_ROOT/app/webroot"
<Directory "CAKEPHP_ROOT/app/webroot">
    AllowOverride All
    Order allow,deny
    Allow from all
</Directory>
```
- Enable URL rewriting on both your JOOMLA_ROOT and CAKEPHP_ROOT
- Bounce Apache
- Update `CAKEPHP_ROOT/index.php` to make the following changes:
```php
[...]
    define('APP_DIR', 'app');
    // Make sure it's not already defined...
    if (!defined('DS'))
      define('DS', DIRECTORY_SEPARATOR);
    define('ROOT', dirname(__FILE__));
[...]
```
- Update `CAKEPHP_ROOT/app/webroot/index.php` to make the following changes:
```php
[...]
//define('CAKE_CORE_INCLUDE_PATH', ROOT . DS . 'lib');
// Modified from Cake/bootstrap.php so we can pre-define FULL_BASE_URL here instead.
if (defined('JAKE')) {
    $s = null;
    if (isset($_SERVER['HTTPS'])) {
        $s = 's';
    }
    $httpHost = $_SERVER['HTTP_HOST'];
    if (isset($httpHost)) {
        define('FULL_BASE_URL', 'http' . $s . '://' . $httpHost . '/app');
    }
    unset($httpHost, $s);
}
/**
* Editing below this line should NOT be necessary.
* Change at your own risk.
*
*/ 
[...] 
App::uses('Dispatcher', 'Routing');
$Dispatcher = new Dispatcher();
$r = new CakeRequest();
if (defined('JAKE'))
{
        $r->url = $url;
        $r->here = $url;
        unset($url);
}
$Dispatcher->dispatch($r, new CakeResponse(array('charset' => Configure::read('App.encoding'))));
```
- If you have upgraded Joomla from a previous 1.5 version that had the previous Jake component installed (which obviously stopped working when you upgraded), uninstall Jake.  In addition, you'll need to manually remove the Jake menu links from the database (which in my experience were not deleted upon uninstall).  To do this, execute the following, replacing your Joomla table prefix as appropriate:
```sql
DELETE FROM `[TABLE_PREFIX]_menu` WHERE `link` LIKE '%jake%';
```
- Merge the "Custom Redirects" section of the included .htaccess file into `JOOMLA_ROOT/.htaccess`.

For more details on configuration, see [this blog post](http://blog.echothis.com/2012/09/26/jake-2-0-released/).

## Usage

After configuration, your CakePHP app is available as-is at `http://joomlaserver/app/`, so you can do stuff like this:

```php
if (defined('JAKE'))
{
    // do something involving Joomla, e.g., get the JUser object
    $user = JFactory::getUser();
    debug($user);
}
```

To add Joomla menu items linked to URL's in your Cake app, in the Joomla Menu Manager, add a new Menu Item of type "External URL" and enter the link path, e.g., `/app/posts/add`.

## Credits

This project was originally developed in 2007 by [Mariano Iglesias](https://github.com/mariano) and [Max](http://www.gigapromoters.com/blog/). Further credits go to Dr. Tarique Sani for his insightful ideas.  It is now maintained by [Rolf Kaiser](http://blog.echothis.com).