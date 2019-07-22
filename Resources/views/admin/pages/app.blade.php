@extends("vaahcms::admin.default.layouts.app")

@section('vaahcms_extend_admin_css')
    <link rel="stylesheet" href="https://unpkg.com/vue-select@3.0.0/dist/vue-select.css">
@endsection


@section('vaahcms_extend_admin_js')
    <script src="{{vh_module_assets_url("Cms", "assets/builds/app.js")}}" defer></script>
@endsection

@section('content')

    <div id="app">


        <top-menu></top-menu>
        <div class="content-body">
            <router-view></router-view>
        </div>


    </div>

@endsection
