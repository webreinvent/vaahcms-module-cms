# vaahcms-module-cms
Cms Module for VaahCMS


#### For Hot Reload add following:
```dotenv
APP_MODULE_CMS_ENV=develop
```


#### To Run Modules Dusk Test:
- Change path of dusk in `phpunit.dusk.xml` to following:
```xml

<directory suffix="Test.php">./VaahCms/Modules/Cms/Tests/Browser</directory>

```

- Then run `php artisan dusk`




### Change Log
- Install `npm install auto-changelog -g`
- To generate `CHANGELOG.md`, run `auto-changelog` in the root folder of the project

Maintain following pre-fixes to your commit messages:
```md
Added:
Changed:
Deprecated:
Removed:
Fixed:
Security:
```