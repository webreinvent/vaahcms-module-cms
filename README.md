# vaahcms-module-cms
Cms Module for VaahCMS


#### For Hot Reload add following:
```dotenv
APP_MODULE_CMS_ENV=develop
```


#### To Run Modules Dusk Test:
- Change path of dusk in `phpunit.dusk.xml` to following:
```xml
...
<directory suffix="Test.php">./VaahCms/Modules/Cms/Tests/Browser</directory>
...
```

- Then run `php artisan dusk`
