<?php

$composer_data = json_decode(file_get_contents(__DIR__ . '/../composer.json'), true);

$settings = [
    "title"=> "Cms",
    "slug"=> "cms",
    "thumbnail"=> "https://placehold.jp/300x160.png",
    "excerpt"=> "Cms",
    "description"=> "Cms",
    "download_link"=> "",
    "author_name"=> "cms",
    "author_website"=> "https://vaah.dev",
    "version"=> $composer_data['version'],
    "is_migratable"=> true,
    "is_sample_data_available"=> false,
    "db_table_prefix"=> "vh_cms_",
    "providers"=> [
        "\\VaahCms\\Modules\\Cms\\Providers\\CmsServiceProvider"
    ],
    "aside-menu-order"=> null
];

$config_data = array_merge($composer_data, $settings);


return $config_data;
