<?php  namespace VaahCms\Modules\Cms\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use VaahCms\Modules\Cms\Entities\Content;
use VaahCms\Modules\Cms\Entities\ContentType;

class SetContent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $content_type = $request->segment(1);

        if(!isset($content_type))
        {
            abort(404);
        }

        $permalink = $request->segment(2);

        if(isset($content_type) && !isset($permalink))
        {
            $permalink = $content_type;
            $content_type = 'pages';
        }

        $content_type = ContentType::where('slug', $content_type)->first();


        if(!$content_type)
        {
            abort(404);
        }

        $content = Content::where('permalink', $permalink)
            ->where('vh_cms_content_type_id', $content_type->id)
            ->first();

        if(!$content)
        {
            abort(404);
        }

        $content = Content::getItem($content->id);

        if($content['status'] != 'success')
        {
            abort(404);
        }

        //for controller
        $request->data = $content['data'];

        //for view
        \View::share('data', $content['data']);

        return $next($request);
    }
}
