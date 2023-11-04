<?php namespace VaahCms\Modules\Cms\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use VaahCms\Modules\Cms\Models\FieldType;
use VaahCms\Modules\Cms\Models\Block;
use WebReinvent\VaahCms\Models\Theme;


class BlocksController extends Controller
{


    //----------------------------------------------------------
    public function __construct()
    {

    }

    //----------------------------------------------------------

    public function getAssets(Request $request)
    {

        $data = [];

        $data['permission'] = [];
        $data['rows'] = config('vaahcms.per_page');

        $data['replace_strings'] = vh_action('getPublicUrls',
            null, 'array');

        $data['field_types'] = FieldType::select('id', 'name', 'slug', 'meta')
            ->get();

        $data['themes'] = Theme::getActiveThemesWithBlockLocations();

        $data['fillable']['except'] = [
            'uuid',
            'created_by',
            'updated_by',
            'deleted_by',
        ];

        $model = new Block();
        $fillable = $model->getFillable();
        $data['fillable']['columns'] = array_diff(
            $fillable, $data['fillable']['except']
        );

        foreach ($fillable as $column)
        {
            $data['empty_item'][$column] = null;
        }

        $data['actions'] = [];

        $response['success'] = true;
        $response['data'] = $data;

        return $response;
    }

    //----------------------------------------------------------
    public function getList(Request $request)
    {
        return Block::getList($request);
    }
    //----------------------------------------------------------
    public function updateList(Request $request)
    {
        return Block::updateList($request);
    }
    //----------------------------------------------------------
    public function listAction(Request $request, $type)
    {
        return Block::listAction($request, $type);
    }
    //----------------------------------------------------------
    public function deleteList(Request $request)
    {
        return Block::deleteList($request);
    }
    //----------------------------------------------------------
    public function createItem(Request $request)
    {
        return Block::createItem($request);
    }
    //----------------------------------------------------------
    public function getItem(Request $request, $id)
    {
        return Block::getItem($id);
    }
    //----------------------------------------------------------
    public function updateItem(Request $request,$id)
    {
        return Block::updateItem($request,$id);
    }
    //----------------------------------------------------------
    public function deleteItem(Request $request,$id)
    {
        return Block::deleteItem($request,$id);
    }
    //----------------------------------------------------------
    public function itemAction(Request $request,$id,$action)
    {
        return Block::itemAction($request,$id,$action);
    }
    //----------------------------------------------------------


}
