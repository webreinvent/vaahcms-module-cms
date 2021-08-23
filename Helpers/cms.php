<?php

/*
|--------------------------------------------------------------------------
| Naming conventions
|--------------------------------------------------------------------------
|
| <prefix>_<types> (plural): will return list of the items with pagination
| <prefix>_<types>_all (plural): will return list of all items
| <prefix>_<types>_count (plural): will return count of items
| <prefix>_<type>  (singular): will return one single record
| $args = ['select' => '', 'where' => [], ]
|
*/



//-----------------------------------------------------------------------------------
use VaahCms\Modules\Cms\Entities\Content;


//-----------------------------------------------------------------------------------
function cms_dynamic_variables()
{
    $list = [
        [
            'name' => '#!PUBLIC:MODULE_URL!#',
            'value' => url('/vaahcms/modules'),
            'detail'=>'Will be replaced with public module url.'
        ],
        [
            'name' => '#!PUBLIC:THEME_URL!#',
            'value' => url('/vaahcms/themes'),
            'detail'=>'Will be replaced with public theme url.'
        ],
        [
            'name' => '#!PUBLIC:STORAGE_URL!#',
            'value' => url('/storage'),
            'detail'=>'Will be replaced with public storage url.'
        ],
        [
            'name' => '#!PUBLIC:BASE_URL!#',
            'value' => url('/'),
            'detail'=>'Will be replaced with public url.',
        ]
    ];

    return $list;

}

function get_content_types(array $args = null)
{




}
//-----------------------------------------------------------------------------------
function get_content_type($id, array $args = null)
{


}
//-----------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------
function get_contents($content_type_slug='pages', array $args = null)
{

    $output = \VaahCms\Modules\Cms\Entities\Content::getContents($content_type_slug, $args);

    return $output;

}
//-----------------------------------------------------------------------------------
function get_content($id, array $args = null, $output=null)
{



}
//-----------------------------------------------------------------------------------
function get_the_content($id, array $args = null, $output='html')
{
    $output = \VaahCms\Modules\Cms\Entities\Content::getTheContent($id, $args, $output);
    return $output;
}
//-----------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------
function get_field(Content $content, $field_slug,
                   $group_slug='default', $type='content',
                   $group_index = 0 , $field_index = null )
{

    if($type=='content')
    {
        return get_content_field($content, $field_slug, $group_slug,$group_index , $field_index);
    } else {
        return get_template_field($content, $field_slug, $group_slug,$group_index , $field_index);
    }
}
//-----------------------------------------------------------------------------------
function get_group(Content $content, $group_slug='default', $type='content',
                   $group_index = null )
{
    if($type=='content')
    {
        return get_group_content_field($content, $group_slug,$group_index,true);
    } else {
//        return get_template_field($content, $field_slug, $group_slug,$group_index , $field_index);
    }
}
//-----------------------------------------------------------------------------------
function get_the_group(Content $content, $group_slug='default', $type='content',
                   $group_index = null )
{
    if($type=='content')
    {
        return get_group_content_field($content, $group_slug,$group_index,false);
    } else {
        return get_template_content_field($$content, $group_slug,$group_index,false);
    }
}
//-----------------------------------------------------------------------------------
function get_the_field(Content $content, $field_slug,
                       $group_slug='default', $type='content',
                       $group_index = 0 , $field_index = null)
{

    if($type=='content')
    {
        return get_content_field($content, $field_slug, $group_slug, $group_index , $field_index, false);
    } else {
        return get_template_field($content, $field_slug, $group_slug,$group_index , $field_index, false);
    }
}
//-----------------------------------------------------------------------------------
function get_content_field(Content $content, $field_slug,
                           $group_slug='default', $group_index = 0 ,
                           $field_index = null, $return_html=true)
{


    foreach ($content->content_form_groups as $arr_group){
        foreach ($arr_group as $key => $group){
            if($group['slug'] === $group_slug){

                if($group_index === $key && isset($arr_group[$group_index])){
                    foreach ($arr_group[$group_index]['fields'] as $field){
                        if($field['slug'] === $field_slug){
                            return setReturnValue($field,$field_index,$return_html);
                        }

                    }
                }



            }
        }
    }

    return null;

}
//-----------------------------------------------------------------------------------
function get_group_content_field(Content $content, $group_slug='default',
                                 $group_index = null , $return_html=true)
{

    if(!$return_html){

        $array_val = [];

        foreach ($content->content_form_groups as $arr_group){
            foreach ($arr_group as $key => $group){
                if($group['slug'] === $group_slug){


                    if($group_index === null){

                        foreach ($group['fields']  as $field){

                            $array_val[$key][$field["slug"]] = setGroupReturnValue($field,false);

                        }

                    }else{

                        if($group_index === $key && isset($arr_group[$group_index])){
                            foreach ($arr_group[$group_index]['fields']  as $field){

                                $array_val[$key][$field["slug"]] = setGroupReturnValue($field,false);

                            }
                        }
                    }

                }
            }
        }

        return $array_val;
    }

    $data = "<ul>";

    foreach ($content->content_form_groups as $arr_group){
        foreach ($arr_group as $key => $group){
            if($group['slug'] === $group_slug){

                if($group_index === null){

                    $data .= '<br/><li> <strong>'.$group['name'].' - ' .($key+1).'</strong></li>';

                    foreach ($group['fields']  as $field){

                        $data .= '<li> <strong>'.$field["name"].'</strong> : '.setGroupReturnValue($field).'</li><br/>';

                    }

                }else{

                    if($group_index === $key && isset($arr_group[$group_index])){
                        $data .= '<br/><li> <strong>'.$group['name'].'</strong></li>';

                        foreach ($arr_group[$group_index]['fields']  as $field){
                            $data .= '<li> <strong>'.$field["name"].'</strong> : '.setGroupReturnValue($field).'</li><br/>';
                        }
                    }
                }

            }
        }
    }


    $data .= "</ul>";

    return $data;
}
//-----------------------------------------------------------------------------------
function get_template_content_field(Content $content, $group_slug='default',
                                 $group_index = null , $return_html=true)
{

    if(!$return_html){

        $array_val = [];

        foreach ($content->template_form_groups as $arr_group){
            foreach ($arr_group as $key => $group){
                if($group['slug'] === $group_slug){


                    if($group_index === null){

                        foreach ($group['fields']  as $field){

                            $array_val[$key][$field["slug"]] = setGroupReturnValue($field,false);

                        }

                    }else{

                        if($group_index === $key && isset($arr_group[$group_index])){
                            foreach ($arr_group[$group_index]['fields']  as $field){

                                $array_val[$key][$field["slug"]] = setGroupReturnValue($field,false);

                            }
                        }
                    }

                }
            }
        }

        return $array_val;
    }

    $data = "<ul>";

    foreach ($content->template_form_groups as $arr_group){
        foreach ($arr_group as $key => $group){
            if($group['slug'] === $group_slug){

                if($group_index === null){

                    $data .= '<br/><li> <strong>'.$group['name'].' - ' .($key+1).'</strong></li>';

                    foreach ($group['fields']  as $field){

                        $data .= '<li> <strong>'.$field["name"].'</strong> : '.setGroupReturnValue($field).'</li><br/>';

                    }

                }else{

                    if($group_index === $key && isset($arr_group[$group_index])){
                        $data .= '<br/><li> <strong>'.$group['name'].'</strong></li>';

                        foreach ($arr_group[$group_index]['fields']  as $field){
                            $data .= '<li> <strong>'.$field["name"].'</strong> : '.setGroupReturnValue($field).'</li><br/>';
                        }
                    }
                }

            }
        }
    }


    $data .= "</ul>";

    return $data;
}


//-----------------------------------------------------------------------------------
function get_template_field(Content $content, $field_slug,
                            $group_slug='default', $group_index = 0,
                            $field_index = null, $return_html=true)
{

    foreach ($content->template_form_groups as $arr_group){
        foreach ($arr_group as $key => $group){
            if($group['slug'] === $group_slug){

                if($group_index === $key && isset($arr_group[$group_index])){
                    foreach ($arr_group[$group_index]['fields'] as $field){
                        if($field['slug'] === $field_slug){
                            return setReturnValue($field,$field_index,$return_html);
                        }

                    }
                }



            }
        }
    }

    return null;
}


//-----------------------------------------------------------------------------------
function setReturnValue($field,$field_index = null,$return_html=true)
{


    if(!$return_html || !isset($field['type'])
        || !isset($field['type']['slug'])){
        if(is_object($field['content'])){
            $value = null;

            if($field['content']){

                if($field['type']['slug'] =='seo-meta-tags'
                    || $field['type']['slug'] =='twitter-card'
                    || $field['type']['slug'] =='facebook-card'
                    || $field['type']['slug'] =='address'){
                    if(is_object($field['content'])){
                        $value = [];

                        foreach ($field['content'] as $key => $item){
                            $value[$key] = $item->content;
                        }
                    }

                }else{
                    $value = $field['content'];
                }
            }

            return $value;
        }
        return $field['content'];
    }

    if(is_array($field['content']) || is_object($field['content'])){
        $field['content'] = json_decode(
            vh_translate_dynamic_strings(json_encode($field['content']))
        );
    }else{
        $field['content'] = vh_translate_dynamic_strings($field['content']);
    }

    $value = null;

    if($field['content']){
        switch($field['type']['slug']){

            case 'seo-meta-tags':
                $value = '<title>'.$field['content']->seo_title->content.'</title>'."\n";
                $value .= '<meta name="description" content="'.$field['content']->seo_description->content.'"/>'."\n";
                $value .= '<meta name="keywords" content="'.$field['content']->seo_keywords->content.'"/>'."\n";
                break;

            case 'twitter-card':
                $value = '<meta name="twitter:card" content="summary" />'."\n";
                $value .= '<meta name="twitter:site" content="'.$field['content']->twitter_site->content.'"/>'."\n";
                $value .= '<meta name="twitter:title" content="'.$field['content']->twitter_title->content.'"/>'."\n";
                $value .= '<meta name="twitter:description" content="'.$field['content']->twitter_description->content.'"/>'."\n";
                $value .= '<meta name="twitter:image" content="'.$field['content']->twitter_image->content.'"/>'."\n";
                break;

            case 'facebook-card':
                $value = '<meta name="og:title" content="'.$field['content']->og_title->content.'"/>'."\n";
                $value .= '<meta name="og:description" content="'.$field['content']->og_description->content.'"/>'."\n";
                $value .= '<meta name="og:image" content="'.$field['content']->og_image->content.'"/>'."\n";
                break;

            case 'address':
                $value = $field['meta']->opening_tag."\n";

                $value .= $field['content']->address_line_1->content;

                if($field['content']->address_line_1->content
                    && $field['content']->address_line_2->content){
                    $value .= ", ";
                }

                $value .= $field['content']->address_line_2->content;

                if($field['content']->address_line_1->content
                    || $field['content']->address_line_2->content){
                    $value .= "</br>";
                }

                $value .= $field['content']->city->content;

                if($field['content']->city->content
                    && $field['content']->state->content){
                    $value .= ", ";
                }

                $value .= $field['content']->state->content;

                if($field['content']->city->content
                    || $field['content']->state->content){
                    $value .= "</br>";
                }

                if($field['content']->landmark->content){
                    $value .= $field['content']->landmark->content."</br>";
                }


                $value .= $field['content']->country->content;

                if($field['content']->country->content
                    && $field['content']->zip_code->content){
                    $value .= ", ";
                }

                $value .= $field['content']->zip_code->content;

                $value .= $field['meta']->closing_tag;
                break;

            case 'json':

                $value = json_encode($field['content']);

//                $value = returnJsonDataInHtml($field,$field['content']);

                break;

            case 'image-group':

                $value = $field['meta']->container_opening_tag."\n";

                foreach ($field['content'] as $item){
                    $value .= $field['meta']->opening_tag."\n";
                    $value .= '<img class="image" src='.$item.'/>'."\n";
                    $value .= $field['meta']->closing_tag."\n";
                }

                $value .= $field['meta']->container_opening_tag;

                break;

            case 'list':
                $value = $field['meta']->container_opening_tag."\n";

                foreach ($field['content'] as  $item){
                    $value .= $field['meta']->opening_tag.$item.$field['meta']->closing_tag;
                }

                $value .= $field['meta']->container_opening_tag;

                break;

            default:

                if(is_string($field['content'])){
                    if($field['is_repeatable']){
                        $temp = $value;
                        $value = [$temp];
                    }else{
                        $value = $field['meta']->opening_tag;
                        $value .= $field['content'];
                        $value .= $field['meta']->closing_tag;
                    }

                }else{
                    $value = $field['content'];
                }


                break;
        }
    }

    if(is_numeric($field_index) && is_array($value) && $field_index >= 0){
        if(isset($value[$field_index])){
            return $value[$field_index];
        }else{
            return null;
        }
    }


    if(is_object($value) || is_array($value)){

        $data = $value;

        $value = $field['meta']->container_opening_tag."\n";

        foreach ($data as $item){
            $value .= $field['meta']->opening_tag.$item.$field['meta']->closing_tag."\n";
        }

        $value .= $field['meta']->container_opening_tag;

    }



    return $value;
}


//-----------------------------------------------------------------------------------
function setGroupReturnValue($field,$return_html = true)
{

    if(!$return_html){
        $value = null;

        if($field['content']){

            if($field['type']['slug'] =='seo-meta-tags'
                || $field['type']['slug'] =='twitter-card'
                || $field['type']['slug'] =='facebook-card'
                || $field['type']['slug'] =='address'){
                if(is_object($field['content'])){
                    $value = [];

                    foreach ($field['content'] as $key => $item){
                        $value[$key] = $item->content;
                    }
                }

            }else{
                $value = $field['content'];
            }
        }

        return $value;
    }



    $value = null;


    if($field['content']){

        if($field['type']['slug'] =='seo-meta-tags'
            || $field['type']['slug'] =='twitter-card'
            || $field['type']['slug'] =='facebook-card'
            || $field['type']['slug'] =='address'){
            if(is_object($field['content'])){
                $value = '<ul>';

                foreach ($field['content'] as $key => $item){
                    $value .= '<li><strong>'.$key.'</strong> : '.$item->content.'</li>';
                }

                $value .= '</ul>';
            }

        }else{

            if(is_object($field['content']) || is_array($field['content'])){

                $value = '<ul>';

                foreach ($field['content'] as $item){
                    $value .= '<li>'.$item.'</li>';
                }

                $value .= '</ul>';

            }else{
                $value = $field['content'];
            }


        }
    }

    return $value;
}


//-----------------------------------------------------------------------------------
function returnJsonDataInHtml($field,$content)
{
    $value = $field['meta']->container_opening_tag."\n";

    foreach ($content as $key => $item){
        if(is_string($item)){
            $value .= $field['meta']->opening_tag.'<strong>'.$key.'</strong>: '.$item.$field['meta']->closing_tag;
        }else{
            $value .= $field['meta']->opening_tag.'<strong>'.$key.'</strong> '.returnJsonDataInHtml($field,$item).$field['meta']->closing_tag;
        }
    }

    $value .= $field['meta']->container_opening_tag;

    return $value;
}
//-----------------------------------------------------------------------------------

