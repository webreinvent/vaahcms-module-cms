# vaahcms-module-cms
Cms Module for VaahCMS

#### New Version Release
- Version should be updated in `composer.json` and `Config/config.php` file


#### For Hot Reload add following:
```dotenv
APP_MODULE_CMS_ENV=develop
```

#### Frontend Routes

```php

<public-url>/{permalink}

<public-url>/{content_type}/{permalink}

<public-url>/taxonomies/{taxonomy_type_slug}/{taxonomy_slug}

<public-url>/search

```

## Helper Methods

#### Theme Locations
```blade
{!! vh_location('top', true) !!}
```

#### Content
```blade
{!! get_content($data) !!}                      //   return HTML format

{!! get_the_content($data) !!}                  //   return DATA format
```

```php
get_content(Content $content, $type=null)

$type = content/template;
```

#### Content List
```blade
{!! get_contents('page') !!}                      //   return HTML format

{!! get_the_contents('page') !!}                  //   return DATA format
```

```php
get_contents($content_type_slug='pages', array $args = null,$has_pagination = true)

$args = [

    'q'                         => 'search_item', 
    'per_page'                  => 5,                                       // default = 20
    'include_groups'            => [],                                      // group_slug
    'exclude_groups'            => [],                                      // group_slug   
    'order'                     => 'name',                                  // default = id      
    'order_by'                  => 'asc',                                   // default = desc      asc/desc/ASC/DESC
    'container_opening_tag'     => '<div class="columns is-multiline">',
    'container_closing_tag'     => '</div>',
    'content_opening_tag'       => '<div class="column is-4">',
    'content_closing_tag'       => '</div>'               

];
```

#### Field
```blade
{!! get_field($data, 'title', 'default') !!}        //   return HTML format

{!! get_the_field($data, 'title', 'default') !!}    //   return DATA format
```

```php
get_field(Content $content, $field_slug,
         $group_slug='default', $type='content',
         $group_index = 0 , $field_index = null)

$type = content/template;

$group_index = 0/1/2/3/4/.....
$field_index = 0/1/2/3/4/.....
```

#### Group
```blade
{!! get_group($data ,'default' ) !!}            //   return HTML format

{!! get_the_group($data ,'default' ) !!}        //   return DATA format
```

```php
get_group(Content $content, $group_slug='default', 
            $type='content, $group_index = null)

$type = content/template;

$group_index = 0/1/2/3/4/.....
```

## API

#### Content
```php
parameter = [

    'q'                         => 'search_item', 
    'per_page'                  => 5,                                       // default = 20
    'include_groups'            => [],                                      // group_slug
    'exclude_groups'            => [],                                      // group_slug
    'order'                     => 'name',                                  // default = id      
    'order_by'                  => 'asc',                                   // default = desc      asc/desc/ASC/DESC              

];

<public-url>/api/cms/contents/{plural_slug}
```

```php
<public-url>/api/cms/contents/{singular_slug}/{content_slug}
```

#### Content Type
```php
<public-url>/api/cms/contents-types

<public-url>/api/cms/contents-types/{slug}
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
