<?php namespace VaahCms\Modules\Cms\Http\Controllers\Backend;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use VaahCms\Modules\Cms\Entities\Content;
use VaahCms\Modules\Cms\Entities\ContentType;
use VaahCms\Modules\Cms\Entities\Menu;
use WebReinvent\VaahCms\Entities\Theme;


class MenusController extends Controller
{

    public $theme;

    //----------------------------------------------------------
    public function __construct()
    {
        $this->theme = vh_get_backend_theme();
    }

    //----------------------------------------------------------

    public function getAssets(Request $request)
    {

        $data['themes'] = Theme::getActiveThemesWithMenuLocations();

        $response['status'] = 'success';
        $response['data'] = $data;

        return response()->json($response);
    }
    //----------------------------------------------------------
    public function postCreate(Request $request)
    {
        $response = Menu::postCreate($request);
        return response()->json($response);
    }
    //----------------------------------------------------------
    public function getList(Request $request)
    {
        $response = Menu::getList($request);
        return response()->json($response);
    }
    //----------------------------------------------------------
    public function getItem(Request $request, $id)
    {
        $response = Menu::getItem($id);
        return response()->json($response);
    }

    //----------------------------------------------------------
    public function getMenuItems(Request $request, $id)
    {
        $response = Menu::getMenuItems($id);
        return response()->json($response);
    }
    //----------------------------------------------------------
    public function postStore(Request $request, $id)
    {
        $response = Menu::postStore($request,$id);
        return response()->json($response);
    }

    //----------------------------------------------------------

    //----------------------------------------------------------
    public function postActions(Request $request, $action)
    {
        $rules = array(
            'inputs' => 'required',
        );

        $validator = \Validator::make( $request->all(), $rules);
        if ( $validator->fails() ) {

            $errors             = errorsToArray($validator->errors());
            $response['status'] = 'failed';
            $response['errors'] = $errors;
            return response()->json($response);
        }

        $response = [];

        $response['status'] = 'success';

        $inputs = $request->all();

        switch ($action)
        {

            //------------------------------------
            case 'bulk-change-status':
                $response = Menu::bulkStatusChange($request);
                break;
            //------------------------------------
            case 'bulk-trash':

                $response = Menu::bulkTrash($request);

                break;
            //------------------------------------
            case 'bulk-restore':

                $response = Menu::bulkRestore($request);

                break;

            //------------------------------------
            case 'bulk-delete':

                $response = Menu::bulkDelete($request);

                break;

            //------------------------------------
            case 'set-as-home-page':

                $response = Menu::setAsHomePage($request);

                break;
            //------------------------------------
        }

        return response()->json($response);

    }

    //----------------------------------------------------------
    public function getContentList(Request $request)
    {

        $list = Content::orderBy('created_at', 'desc');

        if($request->has('q'))
        {
            $list->where(function ($s)use($request){
                $s->where('name', 'LIKE', '%'.$request->q.'%')
                    ->orWhere('id', $request->q );
            });
        }

        $list = $list->take(10)->get();

        $data = [];

        $data['list'] = $list;

        $response['status'] = 'success';
        $response['data'] = $data;

        return response()->json($response);

    }
    //----------------------------------------------------------


}
