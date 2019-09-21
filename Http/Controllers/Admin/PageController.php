<?php

namespace VaahCms\Modules\Cms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use VaahCms\Modules\Cms\Entities\Content;
use VaahCms\Modules\Cms\Entities\Page;
use WebReinvent\VaahCms\Entities\Theme;
use WebReinvent\VaahCms\Entities\ThemeTemplate;

class PageController extends Controller
{



    //----------------------------------------------------------
    public function __construct()
    {

    }

    //----------------------------------------------------------
    public function index()
    {
        return view('cms::admin.pages.pages');
    }
    //----------------------------------------------------------
    public function assets(Request $request)
    {
        $data = [];

        $data['page_templates'] = ThemeTemplate::getAssetsList();
        $data['page_template_default'] = ThemeTemplate::getDefaultPageTemplate();
        $data['page_custom_fields'] = ThemeTemplate::syncTemplateCustomFields($data['page_template_default']['id']);
        $data['page_statuses'] = page_statuses();
        $data['page_visibilities'] = page_visibilities();
        $data['pages_list'] = Page::getAssetsList();

        $response['status'] = 'success';
        $response['data'] = $data;

        return response()->json($response);

    }
    //----------------------------------------------------------
    public function getCustomFields(Request $request, $vh_theme_template_id)
    {

        $data = [];

        $template = ThemeTemplate::syncTemplateCustomFields($vh_theme_template_id);

        $response['status'] = 'success';
        $response['data']['list'] = $template->formGroups()->with(['fields'])->get();

        return response()->json($response);

    }
    //----------------------------------------------------------
    public function storePage(Request $request)
    {
        $rules = array(
            'title' => 'required',
            'name' => 'required',
            'vh_theme_template_id' => 'required',
        );

        $validator = \Validator::make( $request->all(), $rules);
        if ( $validator->fails() ) {

            $errors             = errorsToArray($validator->errors());
            $response['status'] = 'failed';
            $response['errors'] = $errors;
            return response()->json($response);
        }

        $data = [];

        $template = ThemeTemplate::find($request->vh_theme_template_id)->first();

        if($request->has('id'))
        {
            $page = Page::find($request->id);
        } else
        {
            $page = Page::where('vh_theme_template_id', $template->id)
                ->slug(Str::slug($request->title))->first();
        }


        if(!$page)
        {
            $page = new Page();
        }

        $page->fill($request->all());
        $page->save();


        foreach($request->custom_fields as $group)
        {


            foreach ($group['fields'] as $field)
            {
                $insert = [];

                $insert['vh_cms_form_group_id'] = $group['id'];
                $insert['vh_cms_form_field_id'] = $field['id'];
                $insert['contentable_id'] = $page->id;
                $insert['contentable_type'] = Page::class;;

                $content = Content::firstOrCreate($insert);


                if(is_array($field['content']))
                {
                    $insert['content'] = $field['content']['content'];
                } else
                {
                    $insert['content'] = $field['content'];
                }

                $content->fill($insert);

                $content->save();

                $page->contents()->save($content);

            }
        }



        $response['status'] = 'success';
        $response['data'] = $page;

        return response()->json($response);

    }
    //----------------------------------------------------------
    public function getList(Request $request)
    {
        $list = Page::orderBy('created_at', 'DESC');

        if($request->has('q'))
        {
            $list->where(function ($s) use ($request) {
                $s->where('name', 'LIKE', '%'.$request->q.'%')
                    ->orWhere('title', 'LIKE', '%'.$request->q.'%');
            });
        }

        if($request->has('status') && $request->get('status') != 'all')
        {
            $list->status($request->get('status'));
        }


        $i = 0;
        $stats[$i] = ['name' => "All", 'slug' => 'all'];
        $stats[$i]['count'] = Page::count();

        $page_statuses = page_statuses();

        $i++;
        foreach ($page_statuses as $status)
        {
            $stats[$i] = $status;
            $stats[$i]['count'] = Page::status($status['slug'])->count();
            $i++;
        }

        $stats[$i] = ['name' => "Trashed", 'slug' => 'trashed'];
        $stats[$i]['count'] = Page::onlyTrashed()->count();

        $response['status'] = 'success';
        $response['data']['list'] = $list->paginate(10);
        $response['data']['stats'] = $stats;

        return response()->json($response);

    }
    //----------------------------------------------------------

    //----------------------------------------------------------
    public function getPageDetails(Request $request, $id)
    {


        $data = [];

        $page = Page::where('id', $id)->with(['template'])->first();

        if(!$page)
        {
            $response['status'] = 'failed';
            $response['errors'][] = 'Page not found';
            return response()->json($response);
        }

        if(!$page->template()->first())
        {
            $default = ThemeTemplate::getDefaultPageTemplate();
            $page->vh_theme_template_id = $default['id'];
            $page->save();
            $page = Page::where('id', $id)->with(['template'])->first();
        }


        $groups = $page->template->formGroups()->with(['fields'])->get();

        foreach ($groups as $group)
        {
            foreach ($group->fields as $field)
            {
                $insert = [];
                $insert['vh_cms_form_group_id'] = $group['id'];
                $insert['vh_cms_form_field_id'] = $field['id'];
                $insert['contentable_id'] = $page->id;
                $insert['contentable_type'] = Page::class;;
                $field->content = $page->contents()
                    ->firstOrCreate($insert);

            }
        }

        $page->custom_fields = $groups;

        $response['status'] = 'success';
        $response['data'] = $page;

        return response()->json($response);

    }
    //----------------------------------------------------------
    public function getPageCustomFields(Request $request, $id, $vh_theme_template_id)
    {


        $page = Page::where('id', $id)->with(['template'])->first();

        if(!$page)
        {
            $response['status'] = 'failed';
            $response['errors'][] = 'Page not found';
            return response()->json($response);
        }

        $page->vh_theme_template_id = $vh_theme_template_id;
        $page->save();



        $template = ThemeTemplate::syncTemplateCustomFields($vh_theme_template_id);


        $page = Page::where('id', $id)->with(['template'])->first();

        $groups = $page->template->formGroups()->with(['fields'])->get();

        foreach ($groups as $group)
        {
            foreach ($group->fields as $field)
            {

                $page_content = [
                    'vh_cms_form_group_id' => $group->id,
                    'vh_cms_form_field_id' => $field->id,
                ];
                $field->content = $page->contents()
                    ->firstOrCreate($page_content);
            }
        }

        $page->custom_fields = $groups;

        $response['status'] = 'success';
        $response['data'] = $page;

        return response()->json($response);

    }
    //----------------------------------------------------------
    //----------------------------------------------------------



}
