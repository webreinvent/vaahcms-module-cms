<?php namespace VaahCms\Modules\Cms\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use WebReinvent\VaahCms\Entities\Theme;

class CheckCmsDependencies{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        //check at least one theme is installed and active

        $theme = Theme::active()->first();
        if (!$theme)
        {
            return redirect()->to(route('vh.admin.themes'))
                ->withErrors(["Install and activate a theme."]);
        }


        return $next($request);
    }

}