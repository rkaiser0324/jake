jake
====

This is a Joomla 2.5 component that allows you to render a CakePHP 2.2 app inside Joomla.  The project was begun in 2007 by Mariano Iglesias (supporting Joomla 1.5 and CakePHP 1.2), 
and in 2012 I updated it to support Joomla 2.5 and CakePHP 2.2.

To package this into a Joomla component:

1.  Copy the contents of this repo into the a temporary directory somewhere
- Move `administrator/components/com_jake/jake.xml` to `./` 
- Move `administrator/components/com_jake` to `admin/`
- Move `components/com_jake` to `site/`
- If you're not on Windows, zip it up.  Otherwise, multi-select `jake.xml`, `admin/`, and `site/`.  Right-click *Send to Compressed (zipped) folder* to zip it up and rename 
the resulting zipfile as desired
- In the Joomla Extension Manager, browse to the zipfile to install it.

The `.htaccess` file contains the changes needed to support SEO-friendly CakePHP URLs.

For more details on setup, see [this blog post](http://blog.echothis.com/2012/09/26/jake-2-0-released/).