jake
====

This is a Joomla 3.0 component that allows you to render a CakePHP 2.2 app inside Joomla.  The project was begun in 2007 (supporting Joomla 1.5 and CakePHP 1.2), 
and in 2012 I updated it to support Joomla 3.0 and CakePHP 2.2.

## Packaging
Now that Joomla 3.0 offers on-demand "discovery" of new extensions directly from the file system, it's no longer necessary to package them up if you don't want to.  But if you do wish to package this for distribution, 
follow these steps:

1.  Copy the contents of this repo into the a temporary directory somewhere
- Move `administrator/components/com_jake/jake.xml` to `./` 
- Move `administrator/components/com_jake` to `admin/`
- Move `components/com_jake` to `site/`
- If you're not on Windows, zip it up via commandline.  Otherwise, multi-select `jake.xml`, `admin/`, and `site/`.  Right-click *Send to Compressed (zipped) folder* to zip it up and rename 
the resulting zipfile as desired
- In the Joomla Extension Manager, browse to the zipfile to install it.

The `.htaccess` file contains the changes needed to support SEO-friendly CakePHP URLs.

For more details on setup, see [this blog post](http://blog.echothis.com/2012/09/26/jake-2-0-released/).

## Usage

After configuration, your CakePHP app is available as-is at http://joomlaserver/app/, so you can do stuff like this:

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

This project was originally developed in 2007 by [Mariano Iglesias](https://github.com/mariano) and [Max](http://www.gigapromoters.com/blog/). Further credits go to Dr. Tarique Sani for his insightful ideas.  It
is now maintained by [Rolf Kaiser](http://blog.echothis.com).