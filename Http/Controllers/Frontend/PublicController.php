<?php namespace VaahCms\Modules\Cms\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use VaahCms\Modules\Cms\Entities\Content;
use VaahCms\Modules\Cms\Entities\ContentFormField;
use WebReinvent\VaahCms\Entities\Taxonomy;

class PublicController extends Controller
{

    public $theme;

    public function __construct()
    {
        $this->theme = vh_get_theme_slug();
    }

    public function index()
    {
        return 'PublicController';
    }

    //----------------------------------------------------------

    public function page(Request $request, $permalink)
    {
        $theme_slug = $request->data->theme->slug;

        if(!is_null($request->data->template->file_path))
        {
            $file_path = $request->data->template->file_path;
        } else {
            $file_path = 'frontend.templates.'.$request->data->template->slug;
        }

        $view = $theme_slug.'::'.$file_path;

        if ( view()->exists($view)
            && ( Auth::check() || $request->data->status === 'published') ) {
            return view($view);
        } else {
            return abort(404);
        }

    }

    //----------------------------------------------------------

    public function content(Request $request, $content_type, $permalink)
    {

        $theme_slug = $request->data->theme->slug;

        if(!is_null($request->data->template->file_path))
        {
            $file_path = $request->data->template->file_path;
        } else {
            $file_path = 'frontend.templates.'.$request->data->template->slug;
        }

        $view = $theme_slug.'::'.$file_path;

        if (view()->exists($view)) {
            return view($view);
        } else {
            return abort(404);
        }

    }

    //----------------------------------------------------------

    public function taxonomyContents(Request $request, $taxonomy_type_slug, $taxonomy_slug)
    {
        $taxonomy = Taxonomy::where('slug',$taxonomy_slug)
            ->whereHas('type' , function ($t) use ($taxonomy_type_slug){
                $t->where('slug',$taxonomy_type_slug);
            })
            ->with(['contentFormRelations'] )->first();

        $unique_content_ids = [];

        if($taxonomy && count($taxonomy->contentFormRelations) > 0){

            $unique_content_ids = $taxonomy->contentFormRelations()
                ->pluck('vh_cms_content_id')->unique();

        }

        $contents = Content::whereIn('id',$unique_content_ids)
            ->with(['fields' => function($t){
                $t->with(['contentFormRelations' => function($c){
                    $c->with(['relatable']);
                }]);
            },'contentType' => function($q){
                $q->with(['groups' => function($g){
                    $g->with(['fields' => function($f){
                        $f->with(['type']);

                    }]);

                }]);
            }])
            ->orderBy('id','DESC')
            ->paginate(config('vaahcms.per_page'));

        $content_ids = $contents->pluck('id')->toArray();

        $group_fields = ContentFormField::whereIn('vh_cms_content_id',$content_ids)
            ->get();

        $group_fields = collect($group_fields);

        foreach ($contents as $key => $content){

            $contents[$key]['content_form_groups'] = Content::getFormGroupsTest($content,
                'content',$group_fields);

            $arr_template = array();

            $contents[$key]['template_form_groups'] = $arr_template;

        }

        $active_them_namespace = vh_get_active_theme_namespace();

        if (view()->exists($active_them_namespace.'frontend.pages.taxonomy')) {
            $view = $active_them_namespace.'frontend.pages.taxonomy';
        } else {
            $view = $active_them_namespace.'frontend.default';
        }

        return view($view,['data' => $contents,'taxonomy' => $taxonomy]);

    }

    //----------------------------------------------------------

    public function searchContents(Request $request)
    {




        $contents = Content::with(['fields' => function($t){
                $t->with(['contentFormRelations' => function($c){
                    $c->with(['relatable']);
                }]);
            },'contentType' => function($q){
                $q->with(['groups' => function($g){
                    $g->with(['fields' => function($f){
                        $f->with(['type']);

                    }]);

                }]);
            }])
            ->where(function ($q) use ($request){
                $q->where('name', 'LIKE', \DB::raw("'%$request->q%'"))
                    ->orWhere('slug', 'LIKE', \DB::raw("'%$request->q%'"))
                    ->orWhere('permalink', 'LIKE',\DB::raw("'%$request->q%'"));

            })
            ->orderBy('id','DESC')
            ->paginate(config('vaahcms.per_page'));


        $content_ids = $contents->pluck('id')->toArray();

        $group_fields = ContentFormField::whereIn('vh_cms_content_id',$content_ids)
            ->get();

        $group_fields = collect($group_fields);

        foreach ($contents as $key => $content){

            $contents[$key]['content_form_groups'] = Content::getFormGroupsTest($content,
                'content',$group_fields);

            $arr_template = array();

            $contents[$key]['template_form_groups'] = $arr_template;

        }

        $active_them_namespace = vh_get_active_theme_namespace();

        if (view()->exists($active_them_namespace.'frontend.pages.search')) {
            $view = $active_them_namespace.'frontend.pages.search';
        } else {
            $view = $active_them_namespace.'frontend.default';
        }


        return view($view,['data' => $contents]);

    }

    //----------------------------------------------------------
    //----------------------------------------------------------
    //----------------------------------------------------------



}
