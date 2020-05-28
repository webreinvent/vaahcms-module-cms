const mix = require('laravel-mix');
var fs = require('fs');
const fs_extra = require('fs-extra');
let Visualizer = require('webpack-visualizer-plugin');



let publish_folder;
let output_theme_folder;
let source_theme_folder;
let source_vue_folder;




if (mix.inProduction()) {
    /*
     |--------------------------------------------------------------------------
     | Only in Production
     |--------------------------------------------------------------------------
     */
    console.log('---------------------');
    console.log('IN PRODUCTION MODE');
    console.log('---------------------');

    publish_folder = './../../../public/vaahcms/modules/';
    output_theme_folder = "./cms/assets/";
    source_vue_folder = __dirname+'/Vue';

    mix.setPublicPath(publish_folder);

    mix.js(source_vue_folder+"/app.js",  output_theme_folder+'/build/app.js');


} else {

    publish_folder = './../../../public/vaahcms/modules/';
    output_theme_folder = "./cms/assets/";
    source_vue_folder = __dirname+'/Vue';

    mix.setPublicPath(publish_folder);

    mix.js(source_vue_folder+"/app.js",  output_theme_folder+'/build/app.js');

}

mix.webpackConfig({
    watchOptions: {
        aggregateTimeout: 2000,
        poll: 20,
        ignored: [
            '/Config/',
            '/Database/',
            '/Entities/',
            '/Facades/',
            '/Helpers/',
            '/Http/',
            '/Libraries/',
            '/Loaders/',
            '/Observers/',
            '/Providers/',
            '/Resources/',
            '/Routes/',
            '/node_modules/',
            '/Tests/',
            '/Traits/',
        ]
    },

    plugins: [
        new Visualizer()
    ],
});
