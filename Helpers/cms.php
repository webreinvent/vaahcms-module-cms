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
function get_the_field(Content $content, $field_slug,
                       $group_slug='default', $type='content',
                       $group_index = 0 , $field_index = null)
{

    if($type=='content')
    {
        return get_content_field($content, $field_slug, $group_slug,$group_index , $field_index, false);
    } else {
        return get_template_field($content, $field_slug, $group_slug,$group_index , $field_index, false);
    }
}
//-----------------------------------------------------------------------------------
function get_content_field(Content $content, $field_slug,
                           $group_slug='default', $group_index = 0 ,
                           $field_index = null, $return_html=true)
{

    if(isset($content->content_form_groups[0])
        && isset($content->content_form_groups[0][$group_index])
        && $content->content_form_groups[0][$group_index]['slug'] === $group_slug){


        foreach ($content->content_form_groups[0][$group_index]['fields'] as $field){
            if($field['slug'] === $field_slug){
                return setReturnValue($field,$field_index,$return_html);
            }

        }

    }


    return null;

    dd($content->content_form_groups);

    if(!isset($content->content_form_groups)
    || $content->content_form_groups->count() < 1
    )
    {
        return null;
    }

    $group = $content->content_form_groups->where('slug', $group_slug)->first();

    if(!$group)
    {
        return null;
    }


    $field = $group->fields->where('slug', $field_slug)->first();

    if(!$field)
    {
        return null;
    }

    return setReturnValue($field,$return_html);

}
//-----------------------------------------------------------------------------------
function get_group_content_field(Content $content, $group_slug='default',
                                 $group_index = null , $return_html=true)
{

    if(!$return_html){

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
function get_template_field(Content $content, $field_slug,
                            $group_slug='default', $group_index = 0,
                            $field_index = null, $return_html=true)
{

    if(!isset($content->template_form_groups)
        || $content->template_form_groups->count() < 1
    )
    {
        return null;
    }

    $group = $content->template_form_groups->where('slug', $group_slug)->first();

    if(!$group)
    {
        return null;
    }


    $field = $group->fields->where('slug', $field_slug)->first();

    if(!$field)
    {
        return null;
    }

    return setReturnValue($field,$return_html);
}


//-----------------------------------------------------------------------------------
function setReturnValue($field,$field_index = null,$return_html=true)
{


    if(!$return_html || !isset($field['type'])
        || !isset($field['type']['slug'])){
        if($return_html && (is_object($field['content']) || is_array($field['content']))){
            return json_encode($field['content']);
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
                $value = '<address>'."\n";

                $value .= $field['content']->address_line_1->content.', '.$field['content']->address_line_2->content."</br>";
                $value .= $field['content']->city->content.', '.$field['content']->state->content."</br>";
                $value .= $field['content']->landmark->content."</br>";
                $value .= $field['content']->country->content.', '.$field['content']->zip_code->content;

                $value .= '</address>';
                break;

            case 'json':
                $value = json_encode($field['content']);
                break;

            case 'image-group':

                $value = '<div class="image-group field-id-'.$field->id.'" 
            id="field-'.$field->id.'" >'."\n";

                foreach ($field['content'] as $item){
                    $value .= '<div class="image-container">'."\n";
                    $value .= '<img class="image" src='.$item.'/>'."\n";
                    $value .= '</div>'."\n";
                }

                $value .= '</div>'."\n";
                break;

            case 'list':
                $value = '<ul>'."\n";

                foreach ($field['content'] as $item){
                    $value .= '<li>'.$item.'</li>'."\n";
                }
                $value .= '</ul>';
                break;

            default:
                $value = $field['content'];
                break;
        }
    }


    if($field['is_repeatable'] && is_string($value)){
        $temp = $value;
        $value = [$temp];
    }


    if(is_numeric($field_index) && is_array($value) && $field_index >= 0){
        if(isset($value[$field_index])){
            return $value[$field_index];
        }else{
            return null;
        }

    }


    return $value;
}


//-----------------------------------------------------------------------------------
function setGroupReturnValue($field)
{
    $value = null;

    if($field['content']){
        switch($field['type']['slug']){

            case 'seo-meta-tags':
                $value = '<ul>';
                $value .= '<li><strong>Title</strong> : '.$field['content']->seo_title->content.'</li>';
                $value .= '<li><strong>Description</strong> : '.$field['content']->seo_description->content.'</li>';
                $value .= '<li><strong>Keywords</strong> : '.$field['content']->seo_keywords->content.'</li>';
                $value .= '</ul>';
                break;

            case 'twitter-card':
                $value = '<ul>';
                $value .= '<li><strong>Site</strong> : '.$field['content']->twitter_site->content.'</li>';
                $value .= '<li><strong>Title</strong> : '.$field['content']->twitter_title->content.'</li>';
                $value .= '<li><strong>Description</strong> : '.$field['content']->twitter_description->content.'</li>';
                $value .= '<li><strong>Image URl</strong> : '.$field['content']->twitter_image->content.'</li>';
                $value .= '</ul>';
                break;

            case 'facebook-card':
                $value = '<ul>';
                $value .= '<li><strong>Title</strong> : '.$field['content']->og_title->content.'</li>';
                $value .= '<li><strong>Description</strong> : '.$field['content']->og_description->content.'</li>';
                $value .= '<li><strong>Image URl</strong> : '.$field['content']->og_image->content.'</li>';
                $value .= '</ul>';
                break;

            default:
                $value = setReturnValue($field,null,true);
                break;
        }
    }

    return $value;
}
//-----------------------------------------------------------------------------------

