<?php namespace VaahCms\Modules\Cms\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

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
    //----------------------------------------------------------
    //----------------------------------------------------------



}
