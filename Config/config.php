<?php

return  [
    "name"=> "Cms",
    "title"=> "Cms",
    "slug"=> "cms",
    "thumbnail"=> "https://placehold.jp/300x160.png",
    "excerpt"=> "Cms",
    "description"=> "Cms",
    "download_link"=> "",
    "author_name"=> "cms",
    "author_website"=> "https://vaah.dev",
    "version"=> "v0.1.0",
    "is_migratable"=> true,
    "is_sample_data_available"=> false,
    "db_table_prefix"=> "vh_cms_",
    "providers"=> [
        "\\VaahCms\\Modules\\Cms\\Providers\\CmsServiceProvider"
    ],
    "aside-menu-order"=> null
];
