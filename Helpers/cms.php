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
function get_contents($content_type_slug='pages', array $args = null,$has_pagination = true)
{

    $output = \VaahCms\Modules\Cms\Entities\Content::getContents($content_type_slug, $args);

    if($output['status'] == 'success'){

        $val = null;
        $val .= getContentsHtml($output['data']['list'],$args);

        if($has_pagination){
            $val .= returnPaginationHtml($output['data']['list']);
        }

        return $val;

    }

    return $output;

}
//-----------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------
function get_pagination($content_type_slug='pages', array $args = null, $css_type='bulma')
{

    $output = \VaahCms\Modules\Cms\Entities\Content::getContents($content_type_slug, $args);

    if($output['status'] == 'success'){
        return returnPaginationHtml($output['data'],$css_type);
    }

    return $output;

}
//-----------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------
function returnPaginationHtml($contents, $css_type='bulma')
{

    $html = null;

    switch($css_type)
    {
        case 'bootstrap':
            break;

        case 'ulli':
            break;

        case 'bulma':
            $html = get_bulma_pagination($contents);
            break;
    }

    return $html;

}
//-----------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------
function get_bulma_pagination($contents)
{

    $value = '<nav class="pagination" role="navigation" aria-label="pagination">';

    if($contents->previousPageUrl()){
        $value .= '<a class="pagination-previous" 
        href="'.$contents->previousPageUrl().'" >Previous</a>';
    }else{
        $value .= '<a class="pagination-previous" 
        title="This is the first page" disabled>Previous</a>';
    }

    if($contents->nextPageUrl()){
        $value .= '<a class="pagination-next" href="'.$contents->nextPageUrl().'" 
                    >Next page</a>';
    }else{
        $value .= '<a class="pagination-next" 
        title="This is the last page" disabled>Next page</a>';
    }


    $value .= '<ul class="pagination-list">';

    if($contents->url(1) && !$contents->onFirstPage() ){
        $value .= '<li>
                  <a class="pagination-link" href="'.$contents->url(1).'"
                  aria-label="Page 1" aria-current="page">1</a>
                </li>';

        if(($contents->currentPage() - 1) > 2){
            $value .= '<li>
                          <span class="pagination-ellipsis">&hellip;</span>
                        </li>';
        }

        if(($contents->currentPage() - 1) > 1){
            $value .= '<li>
                  <a class="pagination-link" href="'.$contents->previousPageUrl().'"
                   aria-current="page">'.($contents->currentPage() - 1).'</a>
                </li>';
        }
        $value .= '<li>
                  <a class="pagination-link is-current"
                   aria-current="page">'.$contents->currentPage().'</a>
                </li>';

        if(($contents->lastPage() - $contents->currentPage()) > 1){
            $value .= '<li>
                  <a class="pagination-link " href="'.$contents->nextPageUrl().'"
                   aria-current="page">'.($contents->currentPage() + 1).'</a>
                </li>';
        }
    }else{
        $value .= '<li>
                  <a class="pagination-link is-current" 
                  aria-label="Page 1" aria-current="page">1</a>
                </li>';
    }

    if(($contents->lastPage() - $contents->currentPage()) > 2){
        $value .= '<li>
                          <span class="pagination-ellipsis">&hellip;</span>
                        </li>';
    }

    if($contents->lastPage() != $contents->currentPage()){
        $value .= '<li>
                  <a class="pagination-link" href="'.$contents->url($contents->lastPage()).'"
                  aria-label="Page 1" aria-current="page">'.$contents->lastPage().'</a>
                </li>';
    }


    $value .= '</ul>
                </nav>';

    return $value;


}
//-----------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------
function get_the_contents($content_type_slug='pages', array $args = null)
{

    $output = \VaahCms\Modules\Cms\Entities\Content::getContents($content_type_slug, $args);

    if($output['status'] == 'success'){
        return $output['data'];
    }

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
function get_group(Content $content, $group_slug='default', $type='content',
                   $group_index = null )
{
    if($type=='content')
    {
        return get_group_content_field($content, $group_slug,$group_index,true);
    } else {
        return get_template_content_field($content, $group_slug,$group_index,true);
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

        return get_template_content_field($content, $group_slug,$group_index,false);
    }
}
//-----------------------------------------------------------------------------------
function get_content(Content $content, $type=null )
{
    return get_all_group_field($content,$type,true);
}
//-----------------------------------------------------------------------------------
function get_the_content(Content $content, $type=null )
{
    return get_all_group_field($content,$type,false);
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

    $data = null;

    foreach ($content->content_form_groups as $arr_group){
        $data = null;
        foreach ($arr_group as $key => $group){
            if($group['slug'] === $group_slug){

                if($group_index === null){

                    foreach ($group['fields']  as $field){

                        $data .= setReturnValue($field);

                    }

                }else{

                    if($group_index === $key && isset($arr_group[$group_index])){

                        foreach ($arr_group[$group_index]['fields']  as $field){
                            $data .= setReturnValue($field);
                        }
                    }
                }

            }
        }
    }




    return $data;
}
//-----------------------------------------------------------------------------------
function get_all_group_field(Content $content, $type,
                             $return_html=true )
{

    if(!$return_html){

        $array_val = [];

        if(!$type || $type == 'content'){
            foreach ($content->content_form_groups as $arr_group){
                foreach ($arr_group as $key => $group){

                    if($group['fields']){
                        foreach ($group['fields']  as $field){

                            $array_val['content_form_groups'][$group['slug']][$key][$field["slug"]] = setGroupReturnValue($field,false);

                        }
                    }

                }
            }
        }


        if(!$type || $type == 'template'){
            foreach ($content->template_form_groups as $arr_group){
                foreach ($arr_group as $key => $group){

                    if($group['fields']){
                        foreach ($group['fields']  as $field){

                            $array_val['template_form_groups'][$group['slug']][$key][$field["slug"]] = setGroupReturnValue($field,false);

                        }
                    }

                }
            }
        }


        return $array_val;
    }

    $data = null;

    if(!$type || $type == 'content'){
        foreach ($content->content_form_groups as $arr_group){

            foreach ($arr_group as $key => $group){

                if($group['fields']){
                    foreach ($group['fields']  as $field){

                        $data .= setReturnValue($field);

                    }
                }

            }
        }
    }

    if(!$type || $type == 'template'){
        foreach ($content->template_form_groups as $arr_group){

            foreach ($arr_group as $key => $group){

                if($group['fields']){
                    foreach ($group['fields']  as $field){

                        $data .= setReturnValue($field);

                    }
                }

            }
        }
    }

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

    $data = null;

    foreach ($content->template_form_groups as $arr_group){
        $data = null;
        foreach ($arr_group as $key => $group){
            if($group['slug'] === $group_slug){

                if($group_index === null){

                    foreach ($group['fields']  as $field){

                        $data .= setReturnValue($field);

                    }

                }else{

                    if($group_index === $key && isset($arr_group[$group_index])){

                        foreach ($arr_group[$group_index]['fields']  as $field){
                            $data .= setReturnValue($field);
                        }
                    }
                }

            }
        }
    }




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
function getContentsHtml($contents,$args = [])
{
    $value = '';

    if(isset($args['container_opening_tag']) && $args['container_opening_tag']){
        $value = $args['container_opening_tag'];
    }

    foreach ($contents as $content){
        if(isset($args['content_opening_tag']) && $args['content_opening_tag']){
            $value .= $args['content_opening_tag'];
        }
        $value .= get_content($content,null);
        if(isset($args['content_closing_tag']) && $args['content_closing_tag']){
            $value .= $args['content_closing_tag'];
        }
    }

    if(isset($args['container_closing_tag']) && $args['container_closing_tag']){
        $value .= $args['container_closing_tag'];
    }

    return $value;

}


//-----------------------------------------------------------------------------------
function setReturnValue($field,$field_index = null,
                        $return_html=true)
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

    $value = null;

    if($field['content']){
        switch($field['type']['slug']){

            case 'seo-meta-tags':

                if(!$field['meta']->is_hidden){
                    $value = $field['meta']->container_opening_tag."\n";

                    foreach ($field['content'] as  $item){
                        $value .= $field['meta']->opening_tag.$item->content.$field['meta']->closing_tag;
                    }

                    $value .= $field['meta']->container_opening_tag;
                }else{
                    $value = '<title>'.$field['content']->seo_title->content.'</title>'."\n";
                    $value .= '<meta name="description" content="'.$field['content']->seo_description->content.'"/>'."\n";
                    $value .= '<meta name="keywords" content="'.$field['content']->seo_keywords->content.'"/>'."\n";
                }


                break;

            case 'twitter-card':


                if(!$field['meta']->is_hidden){
                    $value = $field['meta']->container_opening_tag."\n";

                    foreach ($field['content'] as  $item){
                        $value .= $field['meta']->opening_tag.$item->content.$field['meta']->closing_tag;
                    }

                    $value .= $field['meta']->container_opening_tag;
                }else{
                    $value = '<meta name="twitter:card" content="summary" />'."\n";
                    $value .= '<meta name="twitter:site" content="'.$field['content']->twitter_site->content.'"/>'."\n";
                    $value .= '<meta name="twitter:title" content="'.$field['content']->twitter_title->content.'"/>'."\n";
                    $value .= '<meta name="twitter:description" content="'.$field['content']->twitter_description->content.'"/>'."\n";
                    $value .= '<meta name="twitter:image" content="'.$field['content']->twitter_image->content.'"/>'."\n";
                }


                break;

            case 'facebook-card':

                if(!$field['meta']->is_hidden){
                    $value = $field['meta']->container_opening_tag."\n";

                    foreach ($field['content'] as  $item){
                        $value .= $field['meta']->opening_tag.$item->content.$field['meta']->closing_tag;
                    }

                    $value .= $field['meta']->container_opening_tag;
                }else{
                    $value = '<meta name="og:title" content="'.$field['content']->og_title->content.'"/>'."\n";
                    $value .= '<meta name="og:description" content="'.$field['content']->og_description->content.'"/>'."\n";
                    $value .= '<meta name="og:image" content="'.$field['content']->og_image->content.'"/>'."\n";
                }

                break;

            case 'address':

                if($field['meta']->is_hidden){
                    return null;
                }

                $value = $field['meta']->container_opening_tag."\n";

                foreach ($field['content'] as  $item){
                    $value .= $field['meta']->opening_tag.$item.$field['meta']->closing_tag;
                }

                $value .= $field['meta']->container_opening_tag;

                break;

            case 'json':

                if($field['meta']->is_hidden){
                    return null;
                }

                $value = json_encode($field['content']);

                break;

            case 'image-group':

                if($field['meta']->is_hidden){
                    return null;
                }

                $value = $field['meta']->container_opening_tag."\n";

                foreach ($field['content'] as $item){
                    $value .= $field['meta']->opening_tag."\n";
                    $value .= '<img class="image" src='.$item.'/>'."\n";
                    $value .= $field['meta']->closing_tag."\n";
                }

                $value .= $field['meta']->container_opening_tag;

                break;

            case 'list':

                if($field['meta']->is_hidden){
                    return null;
                }

                $value = $field['meta']->container_opening_tag."\n";

                foreach ($field['content'] as  $item){
                    $value .= $field['meta']->opening_tag.$item.$field['meta']->closing_tag;
                }

                $value .= $field['meta']->container_opening_tag;

                break;

            case 'relation':

                /*$value = $field['meta']->container_opening_tag."\n";

                $column_name = 'name';

                if(isset($field['relation']) && $field['relation']){
                    foreach ($field['relation'] as  $item){

                        $value .= $field['meta']->opening_tag;

                        $value .= $item['relatable'][$column_name];

                        $value .= $field['meta']->closing_tag;

                    }
                }

                $value .= $field['meta']->container_opening_tag;*/



                break;

            default:

                if($field['meta']->is_hidden){
                    return null;
                }

                if(is_string($field['content'])){
                    if($field['is_repeatable']){
                        $value = [$field['content']];
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

            $value = setReturnValue($field);

        }
    }

    return $value;
}


//-----------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------

