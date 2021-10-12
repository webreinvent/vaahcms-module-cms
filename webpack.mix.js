const mix = require('laravel-mix');
var fs = require('fs');
const fs_extra = require('fs-extra');
let Visualizer = require('webpack-visualizer-plugin');



let publish_folder;
let output_folder;
let source_vue_folder;

source_vue_folder = __dirname+'/Vue';


if (mix.inProduction()) {
    /*
     |--------------------------------------------------------------------------
     | Only in Production
     |--------------------------------------------------------------------------
     */
    console.log('---------------------');
    console.log('IN PRODUCTION MODE');
    console.log('---------------------');

    publish_folder = './Resources/assets/';
    output_folder = "./";

    mix.setPublicPath(publish_folder);
    mix.js(source_vue_folder+"/app.js",  output_folder+'/build/app.js');

} else {

    publish_folder = './../../../public/vaahcms/modules/';
    output_folder = "./cms/assets/";

    mix.setPublicPath(publish_folder);

    mix.js(source_vue_folder+"/app.js",  output_folder+'/build/app.js');

}

mix.webpackConfig({

    watchOptions: {

        aggregateTimeout: 20000,
        poll: 1000,
        ignored: [
            '**/*.php',
            '**/node_modules',
            '/Config/',
            '/Database/',
            '/Helpers/',
            '/Http/',
            '/jobs/',
            '/Libraries/',
            '/Mails/',
            '/Models/',
            '/node_modules/',
            '/Notifications/',
            '/Providers/',
            '/Resources/',
            '/Routes/',
            '/Tests/',
            '/Wiki/',
        ]
    }
});
