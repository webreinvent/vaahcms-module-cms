<?php namespace VaahCms\Modules\Cms\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use VaahCms\Modules\Cms\Models\Content;
use VaahCms\Modules\Cms\Models\Menu;
use WebReinvent\VaahCms\Models\Theme;


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

        try {
            $data = [];

            $data['themes'] = Theme::getActiveThemesWithMenuLocations();

            $data['permission'] = [];
            $data['rows'] = config('vaahcms.per_page');

            $data['fillable']['except'] = [
                'uuid',
                'created_by',
                'updated_by',
                'deleted_by',
            ];

            $model = new Menu();
            $fillable = $model->getFillable();
            $data['fillable']['columns'] = array_diff(
                $fillable, $data['fillable']['except']
            );

            foreach ($fillable as $column) {
                $data['empty_item'][$column] = null;
            }

            $data['actions'] = [];

            $response['success'] = true;
            $response['data'] = $data;

        } catch (\Exception $e) {
            $response = [];
            $response['success'] = false;
            if (env('APP_DEBUG')) {
                $response['errors'][] = $e->getMessage();
                $response['hint'] = $e->getTrace();
            } else {
                $response['errors'][] = 'Something went wrong.';
            }
        }

        return $response;
    }
    //----------------------------------------------------------
    public function postStore(Request $request, $id)
    {
        $response = Menu::postStore($request,$id);
        return response()->json($response);
    }

    //----------------------------------------------------------
    public function getList(Request $request)
    {
        return Menu::getList($request);
    }
    //----------------------------------------------------------
    public function updateList(Request $request)
    {
        return Menu::updateList($request);
    }
    //----------------------------------------------------------
    public function listAction(Request $request, $type)
    {
        return Menu::listAction($request, $type);
    }
    //----------------------------------------------------------
    public function deleteList(Request $request)
    {
        return Menu::deleteList($request);
    }
    //----------------------------------------------------------
    public function createItem(Request $request)
    {
        return Menu::createItem($request);
    }
    //----------------------------------------------------------
    public function getItem(Request $request, $id)
    {
        return Menu::getItem($id);
    }
    //----------------------------------------------------------
    public function updateItem(Request $request,$id)
    {
        return Menu::updateItem($request,$id);
    }
    //----------------------------------------------------------
    public function deleteItem(Request $request,$id)
    {
        return Menu::deleteItem($request,$id);
    }
    //----------------------------------------------------------
    public function itemAction(Request $request,$id,$action)
    {
        return Menu::itemAction($request,$id,$action);
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
            $response['success'] = false;
            $response['errors'] = $errors;
            return response()->json($response);
        }

        $response = [];

        $response['success'] = true;

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


        $response['success'] = true;
        $response['data'] = $list;

        return response()->json($response);

    }



    //----------------------------------------------------------
    public function getMenuItems(Request $request, $id)
    {
        $response = Menu::getMenuItems($id);
        return response()->json($response);
    }

}
