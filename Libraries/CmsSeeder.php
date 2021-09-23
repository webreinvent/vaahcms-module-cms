<?php namespace VaahCms\Modules\Cms\Libraries;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use VaahCms\Modules\Cms\Entities\Content;
use VaahCms\Modules\Cms\Entities\ContentType;
use WebReinvent\VaahCms\Entities\ThemeTemplate;
use WebReinvent\VaahCms\Entities\User;

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
    public static function getThemeLocation($theme, $type, $slug = 'top')
    {
        $theme_location = DB::table('vh_theme_locations')
            ->where('vh_theme_id', $theme->id)
            ->where('type', $type)
            ->where('slug', $slug)
            ->first();

        if(!$theme_location)
        {
            $data = [
                'vh_theme_id'  => $theme->id,
                'type'  => $type,
                'name'  => Str::title($slug),
                'slug'  => $slug,
                'excerpt'  => Str::title($slug).' of every page',
            ];

            DB::table('vh_theme_locations')->insert($data);

            $theme_location = DB::table('vh_theme_locations')
                ->where('vh_theme_id', $theme->id)
                ->where('type', $type)
                ->where('slug', $slug)
                ->first();
        }

        return $theme_location;
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
                ->where('type', $item['type'])
                ->first();


            if(!$record)
            {
                DB::table($table)->insert($item);
            } else{
                DB::table($table)->where('vh_theme_id', $theme->id)
                    ->where($primary_key, $item[$primary_key])
                    ->where('type', $item['type'])
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
    public static function createSampleField($theme_slug, $file_path, $content_type_slug = 'pages')
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

        $content_type = ContentType::where('slug', $content_type_slug)
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

            if(!isset($item['slug']) || !$item['slug']){
                $item['slug'] = Str::slug($item['name']);
            }


            $content = Content::where('slug', $item['slug'])
                ->where('vh_cms_content_type_id', $content_type['id'])
                ->where('vh_theme_id', $theme->id)
                ->where('vh_theme_template_id', $template['id'])
                ->first();

            $is_permalink_exist = Content::where('permalink', $item['permalink']);

            if(!$content){
                $content = new Content();
            }else{
                $is_permalink_exist->where('id','!=', $content->id);
            }

            $is_permalink_exist = $is_permalink_exist->first();

            if($is_permalink_exist){
                $item['permalink'] = Str::random(10).'-'.$item['permalink'];
            }

            $author_id = null;

            if(isset($item['author']) && $item['author']){

                $user = User::where('email',$item['author'])->first();

                if($user){
                    $author_id = $user->id;
                }

            }

            $fillable = [
                'vh_cms_content_type_id' => $content_type['id'],
                'vh_theme_id' => $theme->id,
                'vh_theme_template_id' => $template['id'],
                'name' => $item['name'],
                'slug' => $item['slug'],
                'permalink' => $item['permalink'],
                'author' => $author_id,
                'status' => 'published',
                'is_published_at' => \Carbon::now(),
            ];


            $content->fill($fillable);
            $content->save();

            $json_content = array();
            $json_template = array();

            if(isset($item['content'])){
                $json_content = $item['content'];
            }

            if(isset($item['template'])){
                $json_template = $item['template'];
            }


            $content_groups = self::fillFields($content_type['groups'],$json_content);
            $template_groups = self::fillFields($template['groups'],$json_template);

            Content::storeFormGroups($content, [$content_groups]);
            Content::storeFormGroups($content, [$template_groups]);

        }

    }
    //-------------------------------------------------------

    //-------------------------------------------------------
    public static function fillFields($groups, $json_data = [])
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

                if(isset($json_data[$group['slug']]) && $json_data[$group['slug']]
                    && isset($json_data[$group['slug']][$field['slug']])){

                        $field['content'] = $json_data[$group['slug']][$field['slug']];

                } elseif(
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
    public static function getMenu($theme_location, $menu_slug)
    {
        $menu = DB::table('vh_cms_menus')
            ->where('vh_theme_location_id', $theme_location->id)
            ->where('slug', $menu_slug)
            ->first();

        if(!$menu)
        {
            $data = [
                'vh_theme_location_id'  => $theme_location->id,
                'name'  => Str::title($menu_slug),
                'slug'  => $menu_slug,
            ];

            DB::table('vh_cms_menus')->insert($data);

            $menu = DB::table('vh_cms_menus')
                ->where('vh_theme_location_id', $theme_location->id)
                ->where('slug', $menu_slug)
                ->first();
        }

        return $menu;
    }

    //-------------------------------------------------------
    public static function menus($theme_slug, $file_path)
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

        foreach ($list as $item){

            if(!isset($item['slug']) || !$item['slug']){
                $item['slug'] = Str::slug($item['name']);
            }

            $theme_location = self::getThemeLocation($theme, 'menu', $item['theme_location']);

            $menu = self::getMenu($theme_location, $item['menu_slug']);

            $item['vh_menu_id'] = $menu->id;

            if($item['type'] = 'content'){
                $content = DB::table('vh_cms_contents')
                    ->where('slug', $item['slug'])
                    ->where('vh_theme_id', $theme->id)
                    ->first();

                $item['vh_content_id'] = $content->id;

                $exist = DB::table('vh_cms_menu_items')
                    ->where('slug', $item['slug'])
                    ->where('vh_menu_id', $menu->id)
                    ->where('vh_content_id', $content->id)
                    ->first();
            }else{
                $exist = DB::table('vh_cms_menu_items')
                    ->where('slug', $item['slug'])
                    ->where('vh_menu_id', $menu->id)
                    ->first();
            }

            if($item['parent']){
                $parent_menu = DB::table('vh_cms_menu_items')
                    ->where('slug', $item['parent'])
                    ->where('vh_menu_id', $menu->id)
                    ->first();

                if(!$parent_menu){
                    continue;
                }

                if(!isset($item['sort']) || !$item['sort']){
                    if(!isset(${ $item['menu_slug'].'_'.$parent_menu->slug.'_sort' })){
                        ${ $item['menu_slug'].'_'.$parent_menu->slug.'_sort' } = 0;
                    }else{
                        ${ $item['menu_slug'].'_'.$parent_menu->slug.'_sort' }++;
                    }
                }

                $item['sort'] = ${ $item['menu_slug'].'_'.$parent_menu->slug.'_sort' };

                $item['parent_id'] = $parent_menu->id;

            }else{

                if(!isset($item['sort']) || !$item['sort']){
                    if(!isset(${ $item['menu_slug'].'_sort' })){
                        ${ $item['menu_slug'].'_sort' } = 0;
                    }else{
                        ${ $item['menu_slug'].'_sort' }++;
                    }
                }

                $item['sort'] = ${ $item['menu_slug'].'_sort' };
            }

            unset($item['parent']);
            unset($item['menu_slug']);
            unset($item['theme_location']);


            if(!$exist)
            {
                DB::table('vh_cms_menu_items')->insert($item);
            } else{
                DB::table('vh_cms_menu_items')
                    ->where('slug', $item['slug'])
                    ->where('vh_menu_id', $menu->id)
                    ->update($item);
            }


        }


    }

    //-------------------------------------------------------
    public static function blocks($theme_slug, $file_path)
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

        foreach ($list as $item){

            if(!isset($item['slug']) || !$item['slug']){
                $item['slug'] = Str::slug($item['name']);
            }

            $theme_location = self::getThemeLocation($theme, 'block', $item['theme_location']);

            $item['vh_theme_location_id'] = $theme_location->id;
            $item['vh_theme_id'] = $theme->id;
            $item['is_published'] = true;

            $exist = DB::table('vh_cms_blocks')
                ->where('slug', $item['slug'])
                ->where('vh_theme_location_id', $theme_location->id)
                ->where('vh_theme_id', $theme->id)
                ->first();

            if(!isset($item['sort']) || !$item['sort']){
                if(!isset(${ $item['theme_location'] . '_sort' })){
                    ${ $item['theme_location'] . '_sort' } = 0;
                }else{
                    ${ $item['theme_location'] . '_sort' }++;
                }

                $item['sort'] = ${ $item['theme_location'] . '_sort' };
            }

            unset($item['theme_location']);


            if(!$exist)
            {
                DB::table('vh_cms_blocks')->insert($item);
            } else{
                DB::table('vh_cms_blocks')
                    ->where('slug', $item['slug'])
                    ->where('vh_theme_location_id', $theme_location->id)
                    ->where('vh_theme_id', $theme->id)
                    ->update($item);
            }


        }


    }
    //-------------------------------------------------------


}
