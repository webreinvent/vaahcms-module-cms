@extends("vaahcms::backend.vaahone.layouts.backend")

@section('vaahcms_extend_backend_css')

@endsection


@section('vaahcms_extend_backend_js')
    <script src="{{vh_module_assets_url("Cms", "build/app.js")}}"></script>
@endsection

@section('content')

    <div id="cmsApp">

        <router-view></router-view>

        <vue-progress-bar></vue-progress-bar>

    </div>

@endsection
