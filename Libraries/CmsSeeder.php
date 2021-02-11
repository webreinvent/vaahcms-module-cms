<?php namespace VaahCms\Modules\Cms\Libraries;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use VaahCms\Modules\Cms\Entities\ContentType;
use WebReinvent\VaahCms\Entities\ThemeTemplate;

class CmsSeeder{

    //-------------------------------------------------------
    public static function getJsonData($file_path)
    {
        $jsonString = file_get_contents($file_path);
        $list = json_decode($jsonString, true);
        return $list;
    }

    //-------------------------------------------------------
    public static function getTheme($theme_slug)
    {
        $theme = DB::table('vh_themes')
            ->where('slug', $theme_slug)
            ->first();

        if(!$theme)
        {
            $response['status'] = 'failed';
            $response['errors'][] = 'Theme does not exist';
            return $response;
        }

        return $theme;
    }
    //-------------------------------------------------------
    public static function storeSeeds($theme_slug,
        $table, $list, $primary_key='slug',
        $create_slug=true, $create_slug_from='name'
    )
    {

        $theme = self::getTheme($theme_slug);

        if(is_array($theme)
            && isset($theme['status'])
            && $theme['status'] == 'failed'
        )
        {
            return $theme;
        }



        foreach ($list as $item)
        {
            if($create_slug)
            {
                $item['slug'] = Str::slug($item[$create_slug_from]);
            }

            $item['vh_theme_id'] = $theme->id;

            $record = DB::table($table)
                ->where('vh_theme_id', $theme->id)
                ->where($primary_key, $item[$primary_key])
                ->first();


            if(!$record)
            {
                DB::table($table)->insert($item);
            } else{
                DB::table($table)->where($primary_key, $item[$primary_key])
                    ->update($item);
            }
        }
    }
    //---------------------------------------------------------------
    public static function storeSeedsWithUuid(
        $theme_slug,
        $table, $list, $primary_key='slug',
        $create_slug=true, $create_slug_from='name'
    )
    {

        $theme = self::getTheme($theme_slug);

        if(is_array($theme)
            && isset($theme['status'])
            && $theme['status'] == 'failed'
        )
        {
            return $theme;
        }

        foreach ($list as $item)
        {
            if($create_slug)
            {
                $item['slug'] = Str::slug($item[$create_slug_from]);
            }

            $item['uuid'] = Str::uuid();

            $record = DB::table($table)
                ->where('vh_theme_id', $theme->id)
                ->where($primary_key, $item[$primary_key])
                ->first();


            if(!$record)
            {
                DB::table($table)->insert($item);
            } else{
                DB::table($table)->where($primary_key, $item[$primary_key])
                    ->update($item);
            }
        }

    }
    //-------------------------------------------------------
    public static function themeLocations($theme_slug, $file_path)
    {
        $list = self::getJsonData($file_path);

        self::storeSeeds($theme_slug, 'vh_theme_locations', $list);
    }
    //-------------------------------------------------------
    public static function templates($theme_slug, $file_path)
    {

        $theme = self::getTheme($theme_slug);

        if(is_array($theme)
            && isset($theme['status'])
            && $theme['status'] == 'failed'
        )
        {
            return $theme;
        }

        $templates = self::getJsonData($file_path);

        foreach ($templates as $template){

            $template['template']['slug'] = Str::slug($template['template']['name']);
            $template['template']['vh_theme_id'] = $theme->id;

            $template_exist = ThemeTemplate::where('vh_theme_id', $theme->id)
                ->where('slug', $template['template']['slug'])
                ->first();

            if(!$template_exist)
            {
                $stored_template = ThemeTemplate::insert($template['template']);
            } else{
                $stored_template = ThemeTemplate::where('vh_theme_id', $theme->id)
                    ->where('slug', $template['template']['slug'])
                    ->update($template['template']);
            }

            $stored_template = ThemeTemplate::where('vh_theme_id', $theme->id)
                ->where('slug', $template['template']['slug'])
                ->first();

            $stored_template = ThemeTemplate::find($stored_template->id);

            //template groups
            ThemeTemplate::syncWithFormGroups($stored_template, $template['groups']);

        }

    }
    //-------------------------------------------------------
    public static function contentTypes($file_path)
    {

        $content_types = self::getJsonData($file_path);

        foreach ($content_types as $content_type){

            $exist = DB::table('vh_cms_content_types')
                ->where('slug', $content_type['content']['slug'])
                ->first();

            $content_type['content']['uuid'] = Str::uuid();
            $content_type['content']['content_statuses'] = json_encode($content_type['content']['content_statuses']);

            if(!$exist)
            {
                $stored = DB::table('vh_cms_content_types')->insert($content_type['content']);
            } else{
                $stored = DB::table('vh_cms_content_types')
                    ->where('slug', $content_type['content']['slug'])
                    ->update($content_type['content']);
            }

            $stored = DB::table('vh_cms_content_types')
                ->where('slug', $content_type['content']['slug'])
                ->first();

            $stored = ContentType::find($stored->id);


            //template groups
            ContentType::syncWithFormGroups($stored, $content_type['groups']);


        }
    }
    //-------------------------------------------------------
    //-------------------------------------------------------
    //-------------------------------------------------------


}
