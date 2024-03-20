@extends("vaahcms::backend.vaahtwo.layouts.backend")

@section('vaahcms_extend_backend_css')
    <!--CSS Support for Bulma & Buefy-->

    <!--CSS Support for PrimeVue-->
    <link href="{{vh_get_backend_assets_url()}}/build/vaahtwo.css" rel="stylesheet" media="screen">

    <link href="{{vh_module_assets_url("Cms", "build/index.css")}}" rel="stylesheet" media="screen">

@endsection


@section('vaahcms_extend_backend_js')


    @if(env('MODULE_CMS_ENV') == 'develop')
        <script type="module" src="http://localhost:8367/main.js"></script>
    @else
        <script type="module" src="{{vh_module_assets_url("Cms", "build/index.js")}}"></script>
    @endif

@endsection

@section('content')

    <div class="primevue">
        <div id="appCms">


        </div>
    </div>

@endsection
