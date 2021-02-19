<?php namespace VaahCms\Modules\Cms\Libraries;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use VaahCms\Modules\Cms\Entities\Content;
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
                ThemeTemplate::insert($template['template']);
            } else{
                ThemeTemplate::where('vh_theme_id', $theme->id)
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
                DB::table('vh_cms_content_types')->insert($content_type['content']);
            } else{
                DB::table('vh_cms_content_types')
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
    public static function pages($theme_slug, $file_path)
    {

        $theme = self::getTheme($theme_slug);

        if(is_array($theme)
            && isset($theme['status'])
            && $theme['status'] == 'failed'
        )
        {
            return $theme;
        }

        $list = self::getJsonData($file_path);

        if(count($list) < 1)
        {
            return false;
        }

        $content_type = ContentType::where('slug', 'pages')
            ->with(['groups.fields.type'])
            ->first()->toArray();

        if(!$content_type)
        {
            return false;
        }


        foreach($list as $item)
        {
            $template = ThemeTemplate::where('vh_theme_id', $theme->id)
                ->where('slug', $item['template_slug'])
                ->with(['groups.fields.type'])
                ->first()->toArray();

            if(!$template)
            {
                continue;
            }

            $page = Content::where('slug', $item['slug'])
                ->where('vh_cms_content_type_id', $content_type['id'])
                ->where('vh_theme_id', $theme->id)
                ->where('vh_theme_template_id', $template['id'])
                ->first();

            if(!$page)
            {
                $page = new Content();
            }

            $fillable = [
                'vh_cms_content_type_id' => $content_type['id'],
                'vh_theme_id' => $theme->id,
                'vh_theme_template_id' => $template['id'],
                'name' => $item['name'],
                'slug' => $item['slug'],
                'permalink' => $item['permalink'],
                'status' => 'published',
                'is_published_at' => \Carbon::now(),
            ];


            $page->fill($fillable);
            $page->save();


            $content_groups = self::fillFields($content_type['groups']);
            $template_groups = self::fillFields($template['groups']);

            Content::storeFormGroups($page, $content_groups);
            Content::storeFormGroups($page, $template_groups);

        }

    }
    //-------------------------------------------------------
    public static function fillFields($groups)
    {

        $faker = \Faker\Factory::create();

        if(count($groups) < 1)
        {
            return $groups;
        }

        foreach ($groups as $g_key => $group)
        {

            if(count($group['fields']) < 1)
            {
                continue;
            }

            foreach ($group['fields'] as $key => $field)
            {


                if(
                    isset($field['meta'])
                    && is_object($field['meta'])
                    && isset($field['meta']->default)
                )
                {
                    $field['content'] = $field['meta']->default;
                } else{

                    switch($field['type']['slug']){

                        case 'title':
                        case 'text':
                            $field['content'] = $faker->text(50);
                            break;

                        case 'slug':
                            $field['content'] = Str::slug($faker->text(50));
                            break;

                        case 'uuid':
                            $field['content'] = Str::uuid()->toString();
                            break;

                        case 'email':
                            $field['content'] = $faker->email;
                            break;

                        case 'editor':
                        case 'textarea':
                            $field['content'] = $faker->realText(300, 3);
                            break;

                        case 'image':
                        case 'media':
                            $field['content'] = 'https://via.placeholder.com/150';
                            break;

                        case 'phone-number':
                            $field['content'] = $faker->randomNumber(9);
                            break;

                        case 'time':
                            $field['content'] = $faker->time();
                            break;

                        case 'date':
                            $field['content'] = $faker->date();
                            break;

                        case 'date-time':
                            $field['content'] = $faker->iso8601();
                            break;

                        case 'number':
                            $field['content'] = $faker->randomNumber();
                            break;

                        case 'boolean':
                            $field['content'] = $faker->boolean;
                            break;

                        case 'currency-code':
                            $field['content'] = $faker->currencyCode;
                            break;

                        case 'json':

                            for($i=0;$i<=10;$i++){
                                $data[] = [
                                    'id' => $i,
                                    'name' => $faker->name,
                                    'email' => $faker->email
                                ];
                            }
                            $field['content'] = json_encode($data);
                            break;

                        case 'seo-meta-tags':
                            $field['content'] = self::setMetaTagContent();
                            break;

                        case 'address':
                            $field['content'] = self::setAddressContent();
                            break;

                        case 'twitter-card':
                            $field['content'] = self::setTwitterContent();
                            break;

                        case 'facebook-card':
                            $field['content'] = self::setFacebookContent();
                            break;

                        case 'image-group':

                            $data = [
                                'https://via.placeholder.com/150',
                                'https://via.placeholder.com/150'
                            ];

                            $field['content'] = $data;
                            break;

                        case 'tags':
                            $field['content'] = $faker->text(50);
                            break;

                        case 'password':
                            $field['content'] = 'password';
                            break;

                        case 'price':
                            $field['content'] = $faker->randomNumber(2);
                            break;

                        case 'list':
                            $field['content'] = [$faker->firstName , $faker->firstName];
                            break;

                        default:
                            $field['content'] = null;
                            break;
                    }
                }

                $groups[$g_key]['fields'][$key] = $field;

            }

        }


        return $groups;


    }
    //-------------------------------------------------------
    public static function setMetaTagContent()
    {
        $faker = \Faker\Factory::create();

        $data['seo_description'] = self::fillFieldContent(
            $faker->realText(200, 3),200,
            'SEO Meta Description','textarea',
            'Description of content (maximum 200 characters)');

        $data['seo_keywords'] = self::fillFieldContent(
            $faker->realText(200, 3),200,
            'SEO Meta Keywords','textarea');

        $data['seo_title'] = self::fillFieldContent(
            $faker->text(70),70,
            'SEO Title');

        return $data;


    }
    //-------------------------------------------------------
    public static function setAddressContent()
    {
        $faker = \Faker\Factory::create();

        $data['address_line_1'] = self::fillFieldContent(
            $faker->buildingNumber.' '.$faker->streetName,
            50, 'Address Line 1');

        $data['address_line_2'] = self::fillFieldContent(
            $faker->secondaryAddress,50,
            'Address Line 2');

        $data['city'] = self::fillFieldContent(
            $faker->city,50,
            'City');

        $data['country'] = self::fillFieldContent(
            $faker->country,20,
            'Country');

        $data['landmark'] = self::fillFieldContent(
            $faker->streetName,50,
            'Landmark');

        $data['state'] = self::fillFieldContent(
            $faker->state,50,
            'State');

        $data['zip_code'] = self::fillFieldContent(
            $faker->postcode,20,
            'Zip Code');

        return $data;


    }
    //-------------------------------------------------------
    public static function setTwitterContent()
    {
        $faker = \Faker\Factory::create();

        $data['twitter_description'] = self::fillFieldContent(
            $faker->realText(200, 3),200,
            'twitter:description','textarea',
            'Description of content (maximum 200 characters)');

        $data['twitter_image'] = self::fillFieldContent(
            'https://via.placeholder.com/150',200,
            'twitter:image','text',
            'URL of image to use in the card. 
                                Images must be less than 5MB in size. 
                                JPG, PNG, WEBP and GIF formats are supported.');

        $data['twitter_site'] = self::fillFieldContent(
            'https://twitter.com/',50,
            'twitter:site','test',
            '@username of website. Either twitter:site or twitter:site:id is required.');

        $data['twitter_title'] = self::fillFieldContent(
            $faker->text(50),70,
            'twitter:title','text',
            'Title of content (max 70 characters).');

        return $data;


    }
    //-------------------------------------------------------
    public static function setFacebookContent()
    {
        $faker = \Faker\Factory::create();

        $data['og_description'] = self::fillFieldContent(
            $faker->realText(200, 3),200,
            'og:description','textarea',
            'Description of content (maximum 200 characters)');

        $data['og_image'] = self::fillFieldContent(
            'https://via.placeholder.com/150',200,
            'og:image','text',
            'URL of image to use in the card. 
                                Images must be less than 5MB in size. 
                                JPG, PNG, WEBP and GIF formats are supported.');

        $data['og_title'] = self::fillFieldContent(
            $faker->text(50),70,
            'og:title','text',
            'Title of content (max 70 characters).');

        return $data;


    }
    //-------------------------------------------------------
    public static function fillFieldContent($content,$maxlength,
                                            $name, $type = 'text',
                                            $message = null)
    {

        $response = [
            'content' => $content,
            'maxlength' => $maxlength,
            'name' => $name,
            'type' => $type,
        ];

        if($message){
            $response['message'] = $message;
        }
        return $response;


    }
    //-------------------------------------------------------


}
